<?php

namespace Data;

interface iDataManager {
	public static function getCategories(): array;
	public static function getBooksByCategory(int $categoryId) : array;
}