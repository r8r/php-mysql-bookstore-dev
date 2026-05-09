<?php

namespace Data;

use Bookshop\Category;
use Bookshop\Book;
use Bookshop\User;

class DataManager implements IDatamanager {

	private static $__connection;

	/**
	 * connect to the database
	 *
	 * note: alternatively put those in parameter list or as class variables
	 *
	 * @return \PDO
	 */
	private static function getConnection(): \PDO {
		if (!isset(self::$__connection)) {

			$type = 'mysql';
			$host = 'db';
			$name = 'db';
			$user = 'db';
			$pass = 'db';

			self::$__connection = new \PDO($type . ':host=' . $host . ';dbname=' . $name . ';charset=utf8', $user, $pass);
		}
		return self::$__connection;
	}

	/**
	 * expose the raw PDO connection for debugging or direct use in demos
	 *
	 * @return \PDO
	 */
	public static function exposeConnection(): \PDO {
		return self::getConnection();
	}



	/**
	 * place query
	 *
	 * note: using prepared statements
	 * see the filtering in bindValue()
	 *
	 * @return mixed
	 */
	private static function query(\PDO $connection, string $query, array $parameters = []): \PDOStatement {
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
		} catch (\Exception $e) {
			die($e->getMessage());
//      die('Database Error ' . implode(' | ', $statement->errorInfo()));
		}
		return $statement;
	}

	/**
	 * retrieve an object from the database result set
	 *
	 * @param object $cursor result set
	 * @return object
	 */
	private static function fetchObject($cursor) {
		return $cursor->fetchObject();
	}

	/**
	 * remove the result set
	 *
	 * @param object $cursor result set
	 * @return null
	 */
	private static function close($cursor) {
		$cursor->closeCursor();
	}

	/**
	 * close the database connection
	 *
	 * note: in PDO, simply set the instance to null
	 *
	 * @return void
	 */
	private static function closeConnection() {
		self::$__connection = null;
	}


	/**
	 * get the key of the last inserted item
	 *
	 * @return int
	 */
	private static function lastInsertId($connection) {
		return $connection->lastInsertId();
	}


	public static function getCategories(): array {
		$categories = [];
		$con = self::getConnection();
		$res = self::query($con, "
      SELECT id, name
      FROM categories;
      ");
		while ($cat = self::fetchObject($res)) {
			$categories[] = new Category($cat->id, $cat->name);
		}
		self::close($res);
		self::closeConnection();
		return $categories;
	}
	public static function getBooksByCategory(int $categoryId): array {
		$books = [];
		$con = self::getConnection();
		$res = self::query($con, "
      SELECT id, categoryId, title, author, price
      FROM books WHERE categoryId = ?;
      ", [$categoryId]);
		while ($book = self::fetchObject($res)) {
			$books[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
		}
		self::close($res);
		self::closeConnection();
		return $books;
	}
	public static function getUserByUsername(string $username): ?User {
		$return = null;
		$con = self::getConnection();
		$res = self::query($con, "
      SELECT id, userName, passwordHash
      FROM users WHERE userName = ?;
      ", [$username]);
		if ($user = self::fetchObject($res)) {
			$return = new User($user->id, $user->userName, $user->passwordHash);
		}
		self::close($res);
		self::closeConnection();
		return $return;
	}
	public static function getUserByUserid(int $userid): ?User {
		$return = null;
		$con = self::getConnection();
		$res = self::query($con, "
      SELECT id, userName, passwordHash
      FROM users WHERE id = ?;
      ", [$userid]);
		if ($user = self::fetchObject($res)) {
			$return = new User($user->id, $user->userName, $user->passwordHash);
		}
		self::close($res);
		self::closeConnection();
		return $return;
	}
	public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber): int {

		$con = self::getConnection();

		$con->beginTransaction();

		try {

			self::query($con, "
        INSERT INTO orders (
          userId
          , creditCardNumber
          , creditCardHolder
        ) VALUES (
          ?
          , ?
          , ?
        );
        ", [$userId, $cardNumber, $nameOnCard]);

			$orderId = self::lastInsertId($con);

			foreach ($bookIds as $bookId) {
				self::query($con, "
          INSERT INTO orderedbooks (
            orderId
            , bookId
          ) VALUES (
            ?
            , ?
          );", [$orderId, $bookId]);
			}

			$con->commit();
		} catch (\Exception $e) {
			// one of the queries failed - complete rollback
			$con->rollBack();
			$orderId = 0;
		}
		self::closeConnection();
		return $orderId;
	}

}