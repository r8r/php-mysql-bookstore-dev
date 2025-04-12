<?php

namespace Data;
use Bookshop\User;

interface iDataManager {
	public static function getCategories(): array;
	public static function getBooksByCategory(int $categoryId) : array;
	public static function getUserByUsername(string $userName) : ?User;
	public static function getUserById(int $userId) : ?User;

}