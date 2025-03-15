<?php

namespace Bookshop;

class Entity {
	private int $id;

	public function __construct(int $id) {
		$this->id = intval($id);
	}
	public function getId() : int {
		return $this->id;
	}

}