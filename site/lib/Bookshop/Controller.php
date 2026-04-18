<?php

namespace Bookshop;

/**
 * Controller — Front Controller + Singleton pattern
 *
 * Single entry point for all POST actions: receives the submitted `action`
 * value, dispatches to the appropriate handler, then redirects the client
 * (Post–Redirect–Get). Never renders output itself.
 *
 * Singleton: only one Controller instance exists per request.
 * Session is initialised by bootstrap.php before this class is autoloaded.
 */
class Controller {
	// static strings used in views^ and controller for form fields and action values
	public const string ACTION = 'action';
	public const string PAGE = 'page';
	public const string CC_NAME = 'nameOnCard';
	public const string CC_NUMBER = 'cardNumber';
	public const string USER_NAME = 'userName';
	public const string USER_PASSWORD = 'password';
	/* @TODO: consider replacing with enums */
	public const string ACTION_ADD = 'addToCart';
	public const string ACTION_REMOVE = 'removeFromCart';
	public const string ACTION_ORDER = 'placeOrder';
	public const string ACTION_LOGIN = 'login';
	public const string ACTION_LOGOUT = 'logout';

	/**
	 * Singleton-Instanz — Singleton pattern (Entwurfsmuster)
	 *
	 * Typed nullable property (PHP 7.4+): ?Controller means the value is
	 * either null (not yet created) or a Controller object.
	 * Initialised to null so the first call to getInstance() triggers creation.
	 */
	private static ?Controller $instance = null;

	/**
	 * Returns the single shared Controller instance (lazy initialisation).
	 *
	 * Singleton pattern: only one object of this class is ever created.
	 * The private constructor prevents direct instantiation via `new Controller()`.
	 * Strict null check (=== null) avoids false negatives from type juggling.
	 *
	 * @return Controller
	 */
	public static function getInstance(): Controller {
		if (self::$instance === null) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	/**
	 * Private constructor — prevents direct instantiation.
	 * Callers must use getInstance() to obtain the shared instance.
	 */
	private function __construct() {
	}

	/**
	 * Dispatches the incoming POST request to the appropriate action handler.
	 *
	 * Return type `never` (PHP 8.1+): the method NEVER returns normally —
	 * every code path ends in either a redirect (via Util::redirect() or
	 * forwardRequest()) or an exception. This is enforced by the type system.
	 *
	 * The action value comes from a hidden form field (Controller::ACTION).
	 * A switch statement maps each action string to its handler, keeping all
	 * POST-processing in one place (Front Controller pattern).
	 *
	 * The trailing `throw` after the switch is a safety net: it is
	 * unreachable in practice but satisfies the `never` contract if a case
	 * forgets to redirect or throw.
	 *
	 * @throws \Exception
	 */
	public function invokePostAction(): never {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
		} elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . ' not specified.');
		}

		// reset errors
		$_SESSION['errors'] = null;

		// now process the assigned action
		$action = $_REQUEST[self::ACTION];

		switch ($action) {

			case self::ACTION_ADD:
				// 1. $_REQUEST['bookId'] in das shopping cart legen
				ShoppingCart::add((int) $_REQUEST['bookId']);
				// 2. redirecten zu $_REQUEST[self::PAGE]
				Util::redirect();
				break;

			case self::ACTION_REMOVE:
				// 1. $_REQUEST['bookId'] in das shopping cart legen
				ShoppingCart::remove((int) $_REQUEST['bookId']);
				// 2. redirecten zu $_REQUEST[self::PAGE]
				Util::redirect();
				break;

			case self::ACTION_ORDER:
				$user = AuthenticationManager::getAuthenticatedUser();
				if ($user === null) {
					$this->forwardRequest(['Not logged in.']);
					break;
				}
				if (!$this->processCheckout($_POST[self::CC_NAME], $_POST[self::CC_NUMBER])) {
					$this->forwardRequest(['Checkout failed']);
				}
				break;

			case self::ACTION_LOGIN:
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					self::forwardRequest(['Authentication failed']);
				}
				Util::redirect();
				break;

			case self::ACTION_LOGOUT:
				AuthenticationManager::signOut();
				Util::redirect();
				break;

			default:
				throw new \Exception('Unknown controller action: ' . $action);
		}

		throw new \Exception('Unexpected end of controller action.');
	}

	/**
	 * @param ?string $nameOnCard name as it appears on the credit card
	 * @param ?string $cardNumber 16-digit credit card number
	 * @return bool
	 */
	// ?string — explicit nullable type (PHP 8.1+ deprecates `string $x = null`)
	protected function processCheckout(?string $nameOnCard = null, ?string $cardNumber = null): bool {
		$errors = [];
		$nameOnCard = trim($nameOnCard);
		if ($nameOnCard == null || strlen($nameOnCard) == 0) {
			$errors[] = 'Invalid name on card.';
		}
		if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
			$errors[] = 'Invalid card number. Card number must be sixteen digits.';
		}

		if (sizeof($errors) > 0) {
			$this->forwardRequest($errors);
			return false;
		}

		// check cart
		if (ShoppingCart::size() == 0) {
			$this->forwardRequest(['Shopping cart is empty.']);
			return false;
		}

		// try to place a new order
		$user = AuthenticationManager::getAuthenticatedUser();
		$orderId = \Data\DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);
		if (!$orderId) {
			$this->forwardRequest(['Could not create order.']);
			return false;
		}
		// clear shopping cart and redirect to success page
		ShoppingCart::clear();
		Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

		return true;
	}


	/**
	 * Stores errors in the session and redirects the client back to the form.
	 *
	 * POST–Redirect–Get (PRG) pattern: after a failed POST we redirect instead
	 * of rendering the error inline. This prevents the browser from re-submitting
	 * the form on reload and keeps URLs clean.
	 *
	 * The target URL is taken from the hidden `page` field sent by every form,
	 * so the user lands back on the page they came from.
	 * Errors are written to $_SESSION['errors'] and read by the view after the
	 * redirect — this is the session flash message technique.
	 *
	 * Return type `never`: like invokePostAction(), this method always ends in
	 * exit() or an exception and never returns to its caller.
	 *
	 * @param ?array  $errors  error messages to store in the session
	 * @param ?string $target  URL to forward the request to
	 * @throws \Exception if no target URL can be determined
	 */
	// ?array / ?string — explicit nullable types (PHP 8.1+ deprecates `Type $x = null`)
	protected function forwardRequest(?array $errors = null, ?string $target = null): never {
		// check for given target and try to fall back to previous page if needed
		if ($target == null) {
			if (!isset($_REQUEST[self::PAGE])) {
				throw new \Exception('Missing target for forward.');
			}
			$target = $_REQUEST[self::PAGE];
		}

		// optional - add errors to redirect and process them in view
		if (count($errors) > 0) {
			$_SESSION['errors'] = $errors;
		}

		// forward request to target
		header('location: ' . $target);
		exit();
	}
}
