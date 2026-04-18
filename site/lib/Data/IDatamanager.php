<?php

namespace Data;

use Bookshop\User;

interface IDatamanager {
	public static function getCategories(): array;
	public static function getBooksByCategory(int $categoryId): array;
	public static function getUserByUsername(string $username): ?User;
	public static function getUserByUserid(int $userid): ?User;
	public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber): int;

}