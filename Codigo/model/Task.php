<?php
// file: model/Task.php

require_once(__DIR__."/../core/ValidationException.php");
require_once(__DIR__."/../core/I18n.php");

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
	 * The priority of the Task
	 * @var string
	 */
	private $priority;

	/**
	 * The start date of the Task
	 * @var string
	 */
	private $beginDate;

	/**
	 * The end date of the Task
	 * @var string
	 */
	private $endDate;
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
	public function __construct($id = NULL, $name = NULL,$desc = NULL, $projectID = NULL, $status = NULL, array $users = NULL, $priority = NULL,$beginDate = NULL,$endDate = NULL)
	{
		$this->id = $id;
		$this->name = $name;
		$this->desc = $desc;
		$this->projectID = $projectID;
		$this->status = $status;
		$this->users = $users;
		$this->priority = $priority;	
		$this->beginDate = $beginDate;
		$this->endDate = $endDate;
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
		$this->status = $status;
	}

	/**
	 * Gets the priority of this Task
	 *
	 * @return string The priority of this Task
	 */
	public function getPriority(){
		return $this->priority;
	}

	/**
	 * Sets the priority of the Task
	 *
	 * @param string $priority the priority of this Task
	 * @return void
	 */
	public function setPriority($priority){
		$this->priority = $priority;
	}

	/**
	 * Gets the start date of this Task
	 *
	 * @return string The start date of this Task
	 */
	public function getBeginDate(){
		return $this->beginDate;
	}

	/**
	 * Sets the start date of the Task
	 *
	 * @param string $beginDate the start date of this Task
	 * @return void
	 */
	public function setBeginDate($beginDate){
		$this->beginDate = $beginDate;
	}

	/**
	 * Gets the end date of this Task
	 *
	 * @return string The end date of this Task
	 */
	public function getEndDate(){
		return $this->endDate;
	}

	/**
	 * Sets the end date of the Task
	 *
	 * @param string $endDate the end date of this Task
	 * @return void
	 */
	public function setEndDate($endDate){
		$this->endDate = $endDate;
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
			$errors["name"] = i18n("nombre es obligatorio");
		}

		if (strlen(trim($this->desc)) < 1) {
			$errors["desc"] = i18n("descripcion es obligatoria");
		}

		if (sizeof($this->users) < 1) {
			$errors["users"] = i18n("La tarea debe tener al menos 1 usuario");
		}

		if (strlen(trim($this->status)) < 1) {
			$errors["status"] = i18n("Estado es obligatorio");
		}

		if (strcmp(trim($this->status), "ToDo") != 0 and strcmp(trim($this->status), "Working") != 0 and strcmp(trim($this->status), "Done") != 0) {
			$errors["statusValue"] = i18n("Estado no valido");
		}

		if (strlen(trim($this->priority)) < 1) {
			$errors["priority"] = i18n("Prioridad es obligatoria");
		}

		if (strcmp(trim($this->priority), "Low") != 0 and strcmp(trim($this->priority), "Medium") != 0 and strcmp(trim($this->priority), "High") != 0) {
			$errors["priorityValue"] = i18n("Prioridad no valida");
		}

		// Fechas de la forma AAAA-MM-DD
		if (preg_match('/\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/', $this->beginDate) < 1) {
			$errors["beginDate"] = i18n("Fecha de inicio no valida, debe seguir el formato AAAA-MM-DD");
		}

		if (preg_match('/\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/', $this->endDate) < 1) {
			$errors["endDate"] = i18n("Fecha de fin no valida, debe seguir el formato AAAA-MM-DD");
		}

		//Fecha de inicio anterior a fin
		if (strcmp($this->beginDate,$this->endDate) > 0) {
			$errors["dates"] = i18n("Fecha de fin no valida, debe ser posterior a la fecha de inicio");
		}

		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, i18n("Tarea no Valida"));
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