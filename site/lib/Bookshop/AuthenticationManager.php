<?php

namespace Bookshop;

class AuthenticationManager extends BaseObject {

	/**
	 * check the credentials
	 *
	 * note: sha1 encryption – no salt used!
	 *
	 * @param string $userName   name of the logging in user
	 * @param string $password   password in clear text
	 * @return boolean
	 */
	public static function authenticate(string $userName, string $password) : bool {
		$user = \Data\DataManager::getUserByUserName($userName);
		if ($user != null && $user->getPasswordHash() == hash('sha1', $userName . '|' . $password)) {
			$_SESSION['user'] = $user->getId();
			return true;
		}
		self::signOut();
		return false;
	}

	public static function signOut() : void {
		unset($_SESSION['user']);
	}

	public static function isAuthenticated() : bool {
		return isset($_SESSION['user']);
	}

	public static function getAuthenticatedUser() : ?User {
		return
			self::isAuthenticated() ?
				\Data\DataManager::getUserById($_SESSION['user']) : null;
	}
}