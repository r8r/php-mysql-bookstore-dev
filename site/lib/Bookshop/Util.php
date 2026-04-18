<?php

namespace Bookshop;

class Util extends BaseObject {

	/**
	 * sanitizes output
	 *
	 * @param string $string  the input string
	 * @return string
	 */
	public static function escape(string $string): string {
		return nl2br(htmlentities($string));
	}


	/**
	 * GET parameter "page" adds current page to action so that a redirect
	 * back to this page is possible after successful execution of POST action
	 * if "page" has been set before then just keep the current value (to avoid
	 * problem with "growing URLs" when a POST form is rendered "a second time"
	 * e.g. during a forward after an unsuccessful POS action)
	 *
	 * Be sure to check for invalid / insecure page redirects!!
	 *
	 * @param string $action  uri optional
	 * @param array $params  array key/value pairs
	 * @return string
	 */
	public static function action(string $action, ?array $params = null): string {
		$page = isset($_REQUEST[Controller::PAGE]) ?
			$_REQUEST[Controller::PAGE] :
			$_SERVER['REQUEST_URI'];

		$res = 'index.php?' . Controller::ACTION . '=' . rawurlencode($action) . '&amp;' . Controller::PAGE . '=' .
		       rawurlencode($page);

		if (is_array($params)) {
			foreach ($params as $name => $value) {
				$res .= '&amp;' . rawurlencode($name ?? "") . '=' . rawurlencode($value ?? "");
			}
		}

		return $res;
	}

	/**
	 * redirect with optional url — NOTE: open redirect possible!
	 *
	 * @param string $page  uri optional
	 */
	public static function redirect(?string $page = null): never {
		if ($page == null) {
			$page = isset($_REQUEST[Controller::PAGE]) ?
				$_REQUEST[Controller::PAGE] :
				$_SERVER['REQUEST_URI'];
		}
		header("Location: $page");
		exit();
	}
}