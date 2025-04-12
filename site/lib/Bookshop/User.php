<?php

namespace Bookshop;

class User extends Entity {

	private string $userName;
	private string $passwordHash;

	public function __construct(int $id, string $userName, string $passwordHash) {
		parent::__construct($id);
		$this->userName = $userName;
		$this->passwordHash = $passwordHash;
	}

	public function getUserName(): string {
		return $this->userName;
	}
	
	public function getPasswordHash(): string {
		return $this->passwordHash;
	}

}