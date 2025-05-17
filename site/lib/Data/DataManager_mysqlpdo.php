<?php

namespace Data;
use \Bookshop\Category;
use \Bookshop\Book;
use \Bookshop\User;

/**
 * DataManager
 * Mock Version
 *
 *
 * @package
 * @subpackage
 * @author     John Doe <jd@fbi.gov>
 */
class DataManager implements iDataManager {

	private static $__connection;

	private static function getConnection() {
		if (!isset(self::$__connection)) {

			$type = "mysql";
			$host = "db";
			$name = "db";
			$user = "db";
			$pass = "db";

			$dns = $type . ":host=" . $host . ";dbname=" . $name . ";charset=utf8;";

			self::$__connection = new \PDO($dns, $user, $pass);
		}

		return self::$__connection;
	}

	private static function query(\PDO $connection, string $query, array $parameters = []) : \PDOStatement {
		$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		try {
			$statement = $connection->prepare($query);

			$i = 1;
			foreach ($parameters as $param) {
				if (is_int($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_INT);
				}
				if (is_string($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_STR);
				}
				$i++;
			}

			$statement->execute();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}

		return $statement;
	}

	public static function exposeConnection() {
		return self::getConnection();
	}

	private static function fetchObject($cursor) {
		return $cursor->fetchObject();
	}

	private static function close($cursor) {
		$cursor->closeCursor();
	}

	private static function closeConnection() {
		self::$__connection = null;
	}

	private static function lastInsertId($connection) {
		return $connection->lastInsertId();
	}

	public static function getCategories(): array {
		$result = [];

		$con = self::getConnection();
		$res = self::query($con,
			"SELECT id, name FROM categories"
		);

		while ($cat = self::fetchObject($res)) {
			$result[] = new Category($cat->id, $cat->name);
		}

		self::close($res);
		self::closeConnection();

		return $result;
	}

	public static function getBooksByCategory(int $categoryId) : array {
		$result = [];

		$con = self::getConnection();
		$res = self::query($con,
			"SELECT id, categoryId, title, author, price 
			FROM books
			WHERE categoryId = ?",
			[$categoryId]
		);

		while ($book = self::fetchObject($res)) {
			$result[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
		}

		self::close($res);
		self::closeConnection();

		return $result;
	}
	public static function getUserByUsername(string $userName) : ?User {
		$result = null;

		$con = self::getConnection();
		$res = self::query($con,
			"SELECT id, userName, passwordHash 
			FROM users
			WHERE userName = ?",
			[$userName]
		);

		if ($user = self::fetchObject($res)) {
			$result = new User($user->id, $user->userName, $user->passwordHash);
		}

		self::close($res);
		self::closeConnection();

		return $result;
	}
	public static function getUserById(int $userId) : ?User {
		$result = null;

		$con = self::getConnection();
		$res = self::query($con,
			"SELECT id, userName, passwordHash 
			FROM users
			WHERE id = ?",
			[$userId]
		);

		if ($user = self::fetchObject($res)) {
			$result = new User($user->id, $user->userName, $user->passwordHash);
		}

		self::close($res);
		self::closeConnection();

		return $result;
	}
	public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber) : ?int {
		$orderId = null; // orderId

		$con = self::getConnection();

		$con->beginTransaction();

		try {
			self::query($con,
				"INSERT INTO orders (
	                    userId, 
	                    creditCardNumber,
	                    creditCardHolder
	                    )
											VALUES (
											        ?,
											        ?,
											        ?
											);",
				[$userId, $cardNumber, $nameOnCard]);

			$orderId = self::lastInsertId($con);

			foreach ($bookIds as $bookId) {
				self::query($con,
					"INSERT INTO orderedbooks (
	                    orderId, 
	                   bookId
	                    )
											VALUES (
											        ?,
											        ?
											);",
					[$orderId, $bookId]);
			}
			$con->commit();
		}
		catch (\Exception $e) {
			$con->rollback();
			$orderId = null;
		}

		self::closeConnection();
		return $orderId;
	}


}

	// /mock data