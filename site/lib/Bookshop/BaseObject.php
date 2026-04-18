<?php

namespace Bookshop;

class BaseObject {
	/**
	 * @param string $name
	 * @param array $arguments
	 * @throws \Exception
	 */
	public function __call(string $name, array $arguments) {
		throw new \Exception('Method ' . $name . ' is not declared');
	}

	/**
	 * @param string $name
	 * @param $value
	 * @throws \Exception
	 */
	public function __set(string $name, $value) {
		throw new \Exception('Attribute ' . $name . ' is not declared');
	}

	/**
	 * @param string $name
	 * @throws \Exception
	 */
	public function __get(string $name) {
		throw new \Exception('Attribute ' . $name . ' is not declared');
	}

	/**
	 * @param string $name
	 * @param array $arguments
	 * @throws \Exception
	 */
	public static function __callStatic(string $name, array $arguments) {
		throw new \Exception('Static method ' . $name . ' is not declared');
	}
}
