<?php

namespace Bookshop;

class ShoppingCart extends BaseObject {

	public static function add(int $bookId) : void {
		$cart = self::getCart();
		$cart[$bookId] = $bookId;
		$_SESSION['cart'] = $cart;
	}

	public static function remove(int $bookId) : void {
		$cart = self::getCart();
		unset($cart[$bookId]);
		$_SESSION['cart'] = $cart;
	}

	public static function contains(int $bookId) : bool {
		return array_key_exists($bookId, self::getCart());
	}

	private static function getCart() : array {
		return $_SESSION['cart'] ?? [];
	}

	public static function clear() : void {
		$_SESSION['cart'] = [];
	}

	public static function getAll() : array {
		return self::getCart();
	}

	public static function size() : int {
		return count(self::getCart());
	}

}