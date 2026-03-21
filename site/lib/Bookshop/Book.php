<?php

namespace Bookshop;

class Book extends Entity {

	private int $categoryId;
	private string $title, $author;
	private float $price;

	public function __construct(int $id, int $categoryId, string $title, string $author, float $price) {
		parent::__construct($id);
		$this->categoryId = $categoryId;
		$this->title = $title;
		$this->author = $author;
		$this->price = $price;
	}

	/**
	 * @return int
	 */
	public function getCategoryId(): int {
		return $this->categoryId;
	}

	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getAuthor(): string {
		return $this->author;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float {
		return $this->price;
	}

}