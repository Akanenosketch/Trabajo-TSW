<?php
// file: model/Task.php

require_once(__DIR__."/../core/ValidationException.php");

/**
 * Class Task
 *
 * Represents a Task in the blog. A Task is attached
 * to a Proyect and is assigned to a list of Users.
 *
 */
class Task
{

	/**
	 * The id of the Task
	 * @var string
	 */
	private $id;

	/**
	 * The name of the Task
	 * @var string
	 */
	private $name;

	/**
	 * The description of the Task
	 * @var string
	 */
	private $desc;

	/**
	 * The status of the Task
	 * @var string
	 */
	private $status;

	/**
	 * The list of users of this project
	 * @var mixed
	 */
	private $users;

	/**
	 * The Project ID
	 * @var string
	 */
	private $projectID;

	/**
	 * The constructor
	 *
	 * @param string $id The id of the Task
	 * @param string $name The name of the Task
	 */
	public function __construct($id = NULL, $name = NULL,$desc = NULL, $projectID = NULL, $status = NULL, array $users = NULL)
	{
		$this->id = $id;
		$this->name = $name;
		$this->desc = $desc;
		$this->projectID = $projectID;
		$this->status = $status;
		$this->users = $users;
	}

	/**
	 * Gets the id of this Task
	 *
	 * @return string The id of this Task
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Gets the name of this Task
	 *
	 * @return string The name of this Task
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name of the Task
	 *
	 * @param string $name the name of this Task
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Gets the desc of this Task
	 *
	 * @return string The desc of this Task
	 */
	public function getDesc()
	{
		return $this->desc;
	}

	/**
	 * Sets the desc of the Task
	 *
	 * @param string $desc the desc of this Task
	 * @return void
	 */
	public function setDesc($desc)
	{
		$this->desc = $desc;
	}
	/**
	 * Gets the ProjectID of this Task
	 *
	 * @return string The projectID of this Task
	 */
	public function getProject()
	{
		return $this->projectID;
	}

	/**
	 * Sets the projectID of the Task
	 *
	 * @param string $projectID the projectID of this Task
	 * @return void
	 */
	public function setProject($projectID)
	{
		$this->projectID = $projectID;
	}

	/**
	 * Gets the status of this Task
	 *
	 * @return string The status of this Task
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Sets the status of the Task
	 *
	 * @param string $status the status of this Task
	 * @return void
	 */
	public function setStatus($status)
	{
		$this->name = $status;
	}

	/**
	 * Gets the list of users of this Task
	 *
	 * @return mixed The list of users of this Task
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * Sets the users of the Task
	 *
	 * @param mixed $users the users list of this Task
	 * @return void
	 */
	public function setUsers(array $users)
	{
		$this->users = $users;
	}


	/**
	 * Checks if the current instance is valid
	 * for being inserted in the database.
	 *
	 * @throws ValidationException if the instance is
	 * not valid
	 *
	 * @return void
	 */
	public function checkIsValidForCreate()
	{
		$errors = array();

		if (strlen(trim($this->name)) < 1) {
			$errors["name"] = "name is mandatory";
		}

		if (strlen(trim($this->desc)) < 1) {
			$errors["desc"] = "desc is mandatory";
		}

		if (sizeof($this->users) < 1) {
			$errors["users"] = "Task must have at least 1 user";
		}

		if (strlen(trim($this->status)) < 1) {
			$errors["status"] = "status is mandatory";
		}

		if (strcmp(trim($this->status), "ToDo") != 0 and strcmp(trim($this->status), "Working") != 0 and strcmp(trim($this->status), "Done") != 0) {
			$errors["statusValue"] = "status is not valid";
		}

		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "Task is not valid");
		}
	}

		public function checkIsValidForUpdate()
	{
		$errors = array();

		if (strlen(trim($this->projectID)) < 1) {
			$errors["projectID"] = "projectID is mandatory";
		}

		try {
			$this->checkIsValidForCreate();
		} catch (ValidationException $ex) {
			foreach ($ex->getErrors() as $key => $error) {
				$errors[$key] = $error;
			}
		}
		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "project is not valid");
		}
	}

}
?>