<?php

require_once(__DIR__."/../model/User.php");

require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/ProjectMapper.php");

require_once(__DIR__."/../model/Task.php");
require_once(__DIR__."/../model/TaskMapper.php");

require_once(__DIR__."/BaseRest.php");

/**
 * Class TaskRest
 *
 * It contains operations for CUD tasks.
 *
 * Methods gives responses following Restful standards. Methods of this class
 * are intended to be mapped as callbacks using the URIDispatcher class.
 *
 */
class TaskRest extends BaseRest
{
	private $projectMapper;
	private $taskMapper;

	public function __construct(){
		parent::__construct();

		$this->projectMapper = new ProjectMapper();
		$this->taskMapper = new TaskMapper();
	}
	public function createTask($projectId, $data){

		$project = $this->retrieveProject($projectId);

		// Create and populate the Task object
		$task = new Task();
		$task = $this->loadTask($project, $task, $data);
		try {
			$task->checkIsValidForCreate(); // if it fails, ValidationException
			$this->taskMapper->save($task);
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo (json_encode($e->getErrors()));
		}
	}

	public function updateTask($projectId, $taskId, $data){

		$project = $this->retrieveProject($projectId);
		$task = $this->retrieveTask($project,$taskId);

		try {
			$task = $this->loadTask($project, $task, $data);
			// validate Task object	
			$task->checkIsValidForUpdate(); // if it fails, ValidationException
			//Update Task object in the database
			$this->taskMapper->update($task);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo (json_encode($e->getErrors()));
		}
	}

	public function deleteTask($projectId, $taskId){
		$project = $this->retrieveProject($projectId);
		$task = $this->retrieveTask($project,$taskId);
	
		$this->taskMapper->delete($task);
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	private function retrieveProject($projectId): Project{
		
		$currentUser = parent::authenticateUser();

		// Get the Project object from the database
		$project = $this->projectMapper->findByIdWithAll($projectId);
		if ($project == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo ("Project with id ".$projectId." not found");
			die;
		}
		//Comprobar si el current pertenece al proyecto
		if (!in_array($currentUser, $project->getUsers())) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo ("you are not registered to this project");
			die;
		}

		return $project;
	}

	private function retrieveTask($project,$taskId): Task{

		$task = null;
		foreach ($project->getTasks() as $t) {
			if (strcmp($t->getId(), $taskId) == 0) {
				$task = $t;
			}
		}

		if ($task == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo ("Task with id ".$taskId." not found");
			die;
		}

		return $task;
	}

	private function loadTask($project, $task, $data): Task{

		$task->setProject($project->getId());
		$task->setName($data->name);
		$task->setStatus($data->status);
		$task->setDesc($data->desc);
		$task->setPriority($data->priority);
		$task->setBeginDate($data->beginDate);
		$task->setEndDate($data->endDate);

		//Esta parte cambiarla
		$users = array();
		$userNum = 1;
		foreach ($project->getUsers() as $user) {
			if (isset($data->users["user".$userNum])) {
				array_push($users, $user);
			}
			$userNum++;
		}
		$task->setUsers($users);

		return $task;
	}

}


// URI-MAPPING for this Rest endpoint
$taskRest = new TaskRest();
URIDispatcher::getInstance()
	->map("POST", "/projects/$1/tasks", array($taskRest, "createTask"))
	->map("PUT", "/projects/$1/tasks/$2", array($taskRest, "updateTask"))
	->map("DELETE", "/projects/$1/tasks/$2", array($taskRest, "deleteTask"));