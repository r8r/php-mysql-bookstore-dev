<?php

namespace Bookshop;
use Bookshop\ShoppingCart;
use Data\DataManager;

class Controller extends BaseObject {

	public const ACTION = 'action';
	public const PAGE = 'page';
	public const ACTION_ADD = 'addToCart';
	public const ACTION_REMOVE = 'removeFromCart';
	public const ACTION_LOGIN = 'login';
	public const ACTION_LOGOUT = 'logout';
	public const ACTION_ORDER = 'order';
	public const USER_NAME = 'username';
	public const USER_PASSWORD = 'password';
	public const CC_NAME = 'nameOnCard';
	public const CC_NUMBER = 'cardNumber';



	private static $instance = false;

	public static function getInstance() : Controller {
		if (!self::$instance) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	/**
	 *
	 * processes POST requests and redirects client depending on selected
	 * action
	 *
	 * PHP 8: returns never because either redirect or exception
	 * @throws Exception
	 */
	public function invokePostAction() {

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
		}
		elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . ' not specified.');
		}

		// reset errors
		$_SESSION['errors'] = null;

		// now process the assigned action
		$action = $_REQUEST[self::ACTION];

		switch ($action) {

			case self::ACTION_ADD :
				ShoppingCart::add($_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_REMOVE :
				ShoppingCart::remove($_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_LOGIN :
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					self::forwardRequest(['Wrong username or password']);
				}
				Util::redirect();
				break;

			case self::ACTION_LOGOUT :
				AuthenticationManager::signOut();
				Util::redirect();
				break;

			case self::ACTION_ORDER :
				// check login status
				$user = AuthenticationManager::getAuthenticatedUser();

				if ($user == null) {
					$this->forwardRequest(['Not logged in']);
					break;
				}

				// check order success
				if (!$this->processCheckout($_POST[self::CC_NAME], $_POST[self::CC_NUMBER])) {
					$this->forwardRequest(['Checkout failed']);
				}

				//Util::redirect();
				break;
		}
	}

	protected function processCheckout(string $nameOnCard = null, string $cardNumber = null) : bool {

		$errors = [];

		$nameOnCard = trim($nameOnCard);
		if ($nameOnCard == null || strlen($nameOnCard) == 0) {
			$errors[] = 'Name on card cannot be empty.';
		}

		if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
			$errors[] = 'Invalid card number. Card number must be 16 digits.';
		}

		if (sizeof($errors) > 0) {
			$this->forwardRequest($errors);
			return false;
		}

		$user = AuthenticationManager::getAuthenticatedUser();
		$orderId = DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);

		if (!$orderId) {
			$this->forwardRequest(['Could not create order']);
			return false;
		}

		ShoppingCart::clear();
		Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

		return true;
	}
	
	/**
	 *
	 * @param array $errors : optional assign it to
	 * @param string $target : url for redirect of the request
	 */
	protected function forwardRequest(array $errors = null, string $target = null) : never {
		//check for given target and try to fall back to previous page if needed
		if ($target == null) {
			if (!isset($_REQUEST[self::PAGE])) {
				throw new \Exception('Missing target for forward.');
			}
			$target = $_REQUEST[self::PAGE];
		}

		// optional - add errors to redirect and process them in view
		if (count($errors) > 0) {
			$target .= '&errors=' . urlencode(serialize($errors));
		}

		//forward request to target
		header('location: ' . $target);
		exit();
	}
}