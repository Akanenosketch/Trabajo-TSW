<?php
// file: model/UserMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
 * Class UserMapper
 *
 * Database interface for User entities
 *
 */
class UserMapper
{

	/**
	 * Reference to the PDO connection
	 * @var PDO
	 */
	private $db;

	public function __construct()
	{
		$this->db = PDOConnection::getInstance();
	}

	/**
	 * Saves a User into the database
	 *
	 * @param User $user The user to be saved
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function save($user){
		$stmt = $this->db->prepare("INSERT INTO users(user_mail,username,passwd) values (?,?,?)");
		$stmt->execute(array($user->getUserMail(),$user->getUsername(), $user->getPasswd()));
	}

	/**
	 * Checks if a given usermail is already in the database
	 *
	 * @param string $usermail the usermail to check
	 * @return boolean true if the usermail exists, false otherwise
	 */
	public function usermailExists($usermail){
		$stmt = $this->db->prepare("SELECT count(user_mail) FROM users where user_mail=?");
		$stmt->execute(array($usermail));

			return $stmt->fetchColumn() > 0;
	}

	/**
	 * Checks if a given pair of usermail/password exists in the database
	 *
	 * @param string $usermail the usermail
	 * @param string $passwd the password
	 * @return boolean true the usermail/password exists, false otherwise.
	 */
	public function isValidUser($usermail, $passwd){
		$stmt = $this->db->prepare("SELECT count(user_mail) FROM users where user_mail=? and passwd=?");
		$stmt->execute(array($usermail, $passwd));

			return $stmt->fetchColumn() > 0;
	}

	/**
	 * Loads a user from the database given its email
	 *
	 *
	 * @throws PDOException if a database error occurs
	 * @return User The User instance. NULL
	 * if the User is not found
	 */
	public function findByEmail($usermail){
		$stmt = $this->db->prepare("SELECT * FROM users where user_mail=?");
		$stmt->execute(array($usermail));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user != null) {
			return new user(
				$user["username"],
				$user["user_mail"],
				$user["passwd"]
			);
		} else {
			return NULL;
		}
	}

	/**
	 * Updates a User in the database
	 *
	 * @param User $user The User to be updated
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function update(User $user){
		$stmt = $this->db->prepare("UPDATE users set username=?,passwd=? where user_mail=?");
		$stmt->execute(array($user->getUsername(), $user->getPasswd(),$user->getUserMail()));
	}

	public function findAll()
	{
		$stmt = $this->db->prepare("SELECT * FROM users");
		$stmt->execute();
		$users_DB = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$users = array();

		foreach ($users_DB as $user) {
			array_push($users, new User(
				$user["username"],
				$user["user_mail"],
				$user["passwd"]
			));
		}
		return $users;
	}
}
?>