<?php

namespace Bookshop;

SessionContext::create();

class ShoppingCart extends BaseObject {
/*
	array(
		1 => 1,
		5 => 5
	);
*/

	private static function getCart() {
		return $_SESSION['cart'] ?? [];
	}

	private static function storeCart(array $cart) : void {
		$_SESSION['cart'] = $cart;
	}

	public static function add(int $bookId) : void {
		$cart = self::getCart();
		$cart[$bookId] = $bookId;
		self::storeCart($cart);
	}

	public static function remove(int $bookId) : void {
		$cart = self::getCart();
		unset($cart[$bookId]);
		self::storeCart($cart);
	}

	public static function contains(int $bookId) : bool {
		$cart = self::getCart();
		return isset($cart[$bookId]);
	}

	public static function size() : int {
		$cart = self::getCart();
		return sizeof($cart);
	}

	public static function getAll() : array {
		return self::getCart();
	}

	public static function clear() : void {
		self::storeCart([]);
	}


}