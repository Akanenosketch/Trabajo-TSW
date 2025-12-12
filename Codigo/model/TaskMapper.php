<?php
// file: model/TaskMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/Task.php");

/**
 * Class TaskMapper
 *
 * Database interface for Task entities
 *
 * @author lipido <lipido@gmail.com>
 */
class TaskMapper
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
	 * Saves a Task
	 *
	 * @param Task $Task The Task to save
	 * @throws PDOException if a database error occurs
	 * @return int The new Task id
	 */
	public function save(Task $task)
	{
		$stmt = $this->db->prepare("INSERT INTO tasks(task_name, project_id, task_status,task_desc) values (?,?,?,?)");
		$stmt->execute(array($task->getName(), $task->getProject(), $task->getStatus(), $task->getDesc()));
		$toRet = $this->db->lastInsertId();

		$stmt = $this->db->prepare("INSERT INTO users_on_tasks(user_mail,project_id,task_id) values (?,?,?)");

		foreach ($task->getUsers() as $user) {
			$stmt->execute(array($user->getUserMail(), $task->getProject(), $toRet));
		}

		return $toRet;
	}


	/**
	 * Updates a Task in the database
	 *
	 * @param Task $task The Task to be updated
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function update(Task $task)
	{
		$stmt = $this->db->prepare("UPDATE tasks set task_name=?,task_status=?,task_desc=? where task_id=?");
		$stmt->execute(array($task->getName(), $task->getStatus(), $task->getDesc(), $task->getId()));

		$stmt = $this->db->prepare("SELECT user_mail FROM users_on_tasks WHERE task_id=?");
		$stmt->execute(array($task->getId()));
		$users_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$users = array();
		foreach ($users_db as $user) {
				array_push($users, $user["user_mail"]);

		}
		$stmt = $this->db->prepare("INSERT INTO users_on_tasks(user_mail,project_id,task_id) values (?,?,?)");
		$stmt2 = $this->db->prepare("DELETE FROM users_on_tasks WHERE user_mail=? and task_id=?");
		$repeatedUsers = array();
		foreach ($task->getUsers() as $user) {
			if (!in_array($user->getUserMail(), $users)) {
				$stmt->execute(array($user->getUserMail(), $task->getProject(), $task->getId()));
			} else{
				array_push($repeatedUsers,$user->getUserMail()); 
			}
		}

		//Remove users from task
		foreach ($users as $user) {
			if (!in_array($user, $repeatedUsers)) {
				$stmt2->execute(array($user,$task->getId()));
			}
		}
	}


	/**
	 * Deletes a Task from the database
	 *
	 * @param String $id The Id of the task to be deleted
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function delete(String $id)
	{
		$stmt = $this->db->prepare("DELETE from tasks WHERE task_id=?");
		$stmt->execute(array($id));
	}
}
?>