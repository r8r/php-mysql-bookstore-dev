<?php

namespace Bookshop;
use Bookshop\ShoppingCart;

class Controller extends BaseObject {

	public const ACTION = 'action';
	public const PAGE = 'page';
	public const ACTION_ADD = 'addToCart';
	public const ACTION_REMOVE = 'removeFromCart';
	public const ACTION_LOGIN = 'login';
	public const ACTION_LOGOUT = 'logout';
	public const USER_NAME = 'username';
	public const USER_PASSWORD = 'password';


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
		}
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
			$_SESSION['errors'] = $errors;
		}

		//forward request to target
		header('location: ' . $target);
		exit();
	}
}