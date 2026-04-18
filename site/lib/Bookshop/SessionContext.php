<?php

namespace Bookshop;

class SessionContext extends BaseObject {

	private static bool $started = false;

	public static function create() {
		if (!self::$started && session_status() == PHP_SESSION_NONE) {
			session_start();
			self::$started = true;
		}
	}

}