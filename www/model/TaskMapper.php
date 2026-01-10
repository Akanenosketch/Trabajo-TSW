<?php
// file: model/TaskMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/Task.php");
require_once(__DIR__."/../model/User.php");

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
		$stmt = $this->db->prepare("INSERT INTO tasks(task_name, project_id, task_status,task_desc, task_priority,begin_date,end_date) values (?,?,?,?,?,?,?)");
		$stmt->execute(array($task->getName(), $task->getProject(), $task->getStatus(), $task->getDesc(), $task->getPriority(), $task->getBeginDate(), $task->getEndDate()));
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
		// 1. Update the main task details
		$stmt = $this->db->prepare("UPDATE tasks SET task_name=?, task_status=?, task_desc=?, task_priority=?, begin_date=?, end_date=? WHERE task_id=?");
		$stmt->execute(array(
			$task->getName(),
			$task->getStatus(),
			$task->getDesc(),
			$task->getPriority(), 
			$task->getBeginDate(),
			$task->getEndDate(),
			$task->getId()
		));

		// 2. Clear all existing users for this task first
		$stmtDel = $this->db->prepare("DELETE FROM users_on_tasks WHERE task_id = ?");
		$stmtDel->execute(array($task->getId()));

		// 3. Insert the currently selected users
		$stmtIns = $this->db->prepare("INSERT INTO users_on_tasks (user_mail, project_id, task_id) VALUES (?,?,?)");
		foreach ($task->getUsers() as $user) {
			// Handle both User objects or raw strings
			$mail = is_object($user) ? $user->getUserMail() : $user;
			$stmtIns->execute(array($mail, $task->getProject(), $task->getId()));
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
