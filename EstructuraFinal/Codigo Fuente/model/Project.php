<?php
// file: model/Project.php

require_once(__DIR__."/../core/ValidationException.php");

/**
 * Class Project
 *
 * Represents a Project. A Project is assigned to at least 1 User
 * and contains a list of Tasks
 *
 */
class Project
{

	/**
	 * The id of this project
	 * @var string
	 */
	private $id;

	/**
	 * The name of this project
	 * @var string
	 */
	private $name;

	/**
	 * The list of users of this project
	 * @var mixed
	 */
	private $users;

	/**
	 * The list of tasks of this project
	 * @var mixed
	 */
	private $tasks;

	/**
	 * The constructor
	 *
	 * @param string $id The id of the project
	 * @param string $name The id of the project
	 * @param mixed $users The list of users
	 * @param mixed $tasks The list of tasks
	 */
	public function __construct($id = NULL, $name = NULL, array $users = NULL, array $tasks = NULL)
	{
		$this->id = $id;
		$this->name = $name;
		$this->users = $users;
		$this->tasks = $tasks;

	}

	/**
	 * Gets the id of this project
	 *
	 * @return string The id of this project
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Gets the bame of this project
	 *
	 * @return string The name of this project
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name of this project
	 *
	 * @param string $name the name of this project
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Gets the list of users of this project
	 *
	 * @return mixed The list of users of this project
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * Sets the users of the project
	 *
	 * @param mixed $users the users list of this project
	 * @return void
	 */
	public function setUsers(array $users)
	{
		$this->users = $users;
	}

	/**
	 * Gets the list of tasks of this project
	 *
	 * @return mixed The list of tasks of this project
	 */
	public function getTasks()
	{
		return $this->tasks;
	}

	/**
	 * Sets the tasks of the project
	 *
	 * @param mixed $tasks the tasks list of this project
	 * @return void
	 */
	public function setTasks(array $tasks)
	{
		$this->tasks = $tasks;
	}

	/**
	 * Checks if the current instance is valid
	 * for being updated in the database.
	 *
	 * @throws ValidationException if the instance is
	 * not valid
	 *
	 * @return void
	 */
	public function checkIsValidForCreate()
	{
		$errors = array();
		if (strlen(trim($this->name)) == 0) {
			$errors["name"] = "name is mandatory";
		}

		if (sizeof($this->users) < 1) {
			$errors["users"] = "Project must have at least 1 user";
		}

		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "project is not valid");
		}
	}

	/**
	 * Checks if the current instance is valid
	 * for being updated in the database.
	 *
	 * @throws ValidationException if the instance is
	 * not valid
	 *
	 * @return void
	 */
	public function checkIsValidForUpdate()
	{
		$errors = array();

		if (!isset($this->id)) {
			$errors["id"] = "id is mandatory";
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

	public function getTaskNumberByType($type): int{
		$toRet = 0;
		foreach ($this->tasks as $task) {
 			 if (strcmp($task->getStatus(), $type) == 0 ){
				$toRet++;
			 }	
	}
		return $toRet; 
	}

	public function getCompletedPercent(){
		$total = count($this->tasks);
		$completed = $this->getTaskNumberByType("Done");
		if($total == 0) return 0;
		return $completed *100/$total;
	}
}
?>