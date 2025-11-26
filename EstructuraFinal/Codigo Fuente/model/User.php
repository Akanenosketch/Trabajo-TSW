<?php
// file: model/User.php

require_once(__DIR__."/../core/ValidationException.php");

/**
 * Class User
 *
 * Represents a User in the app
 *
 */
class User
{

	/**
	 * The user name of the user
	 * @var string
	 */
	private $username;

	/**
	 * The email of the user
	 * @var string
	 */
	private $user_mail;

	/**
	 * The password of the user
	 * @var string
	 */
	private $passwd;

	/**
	 * The constructor
	 *
	 * @param string $username The name of the user
	 * @param string $user_mail The mail of the user
	 * @param string $passwd The password of the user
	 */
	public function __construct($username = NULL, $user_mail = NULL, $passwd = NULL)
	{
		$this->username = $username;
		$this->user_mail = $user_mail;
		$this->passwd = $passwd;
	}

	/**
	 * Gets the username of this user
	 *
	 * @return string The username of this user
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Sets the username of this user
	 *
	 * @param string $username The username of this user
	 * @return void
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * Gets the user_mail of this user
	 *
	 * @return string The user_mail of this user
	 */
	public function getUserMail()
	{
		return $this->user_mail;
	}

	/**
	 * Sets the user_mail of this user
	 *
	 * @param string $user_mail The user_mail of this user
	 * @return void
	 */
	public function setUserMail($user_mail)
	{
		$this->user_mail = $user_mail;
	}

	/**
	 * Gets the password of this user
	 *
	 * @return string The password of this user
	 */
	public function getPasswd()
	{
		return $this->passwd;
	}
	/**
	 * Sets the password of this user
	 *
	 * @param string $passwd The password of this user
	 * @return void
	 */
	public function setPassword($passwd)
	{
		$this->passwd = $passwd;
	}

	/**
	 * Checks if the current user instance is valid
	 * for being registered in the database
	 *
	 * @throws ValidationException if the instance is
	 * not valid
	 *
	 * @return void
	 */
	public function checkIsValidForRegister()
	{
		$errors = array();

		//Comprobacion de PK lo hace el controller, aqui solo comprueba formatos

		// Correos de la forma example123@example123.example
		if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $this->user_mail) < 1) {
			$errors["user_mail"] = "Usermail must use a valid format example@example";
		}

		if (strlen(trim($this->username)) < 4) {
			$errors["username"] = "Username is mandatory";

		}
		if (strlen(trim($this->passwd)) < 6) {
			$errors["passwd"] = "Password is mandatory";
		}

		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "user is not valid");
		}
	}
}
<?php