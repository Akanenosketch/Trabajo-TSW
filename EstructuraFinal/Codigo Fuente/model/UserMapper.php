<?php
// file: model/UserMapper.php

require_once(__DIR__ . "/../core/PDOConnection.php");

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
	public function save($user)
	{
		$stmt = $this->db->prepare("INSERT INTO users values (?,?,?)");
		$stmt->execute(array($user->getUserMail(), $user->getUsername(), $user->getPasswd()));
	}

	/**
	 * Checks if a given usermail is already in the database
	 *
	 * @param string $usermail the usermail to check
	 * @return boolean true if the usermail exists, false otherwise
	 */
	public function usernameExists($usermail)
	{
		$stmt = $this->db->prepare("SELECT count(user_mail) FROM users where user_mail=?");
		$stmt->execute(array($usermail));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	/**
	 * Checks if a given pair of usermail/password exists in the database
	 *
	 * @param string $usermail the usermail
	 * @param string $passwd the password
	 * @return boolean true the usermail/password exists, false otherwise.
	 */
	public function isValidUser($usermail, $passwd)
	{
		$stmt = $this->db->prepare("SELECT count(user_mail) FROM users where user_mail=? and passwd=?");
		$stmt->execute(array($usermail, $passwd));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}
}
