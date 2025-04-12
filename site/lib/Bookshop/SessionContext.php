<?php

namespace Bookshop;

/**
 * SessionContext - check in ../bootstrap.php
 * @package
 * @subpackage
 * @author     John Doe <jd@fbi.gov>
 */
class SessionContext extends BaseObject {

	private static $exists = false;

	/**
	 * checkt ob eine session angelegt ist, wenn nicht, macht es das
	 *
	 * @return boolean
	 */
	public static function create() : bool {
		if (!self::$exists) {
			self::$exists = session_start();
		}
		return self::$exists;
	}

}
