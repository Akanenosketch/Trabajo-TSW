<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/ProjectMapper.php");

require_once(__DIR__."/../model/Task.php");

require_once(__DIR__."/BaseRest.php");

/**
 * Class ProjectRest
 *
 * It contains operations for CRUDL projects.
 *
 * Methods gives responses following Restful standards. Methods of this class
 * are intended to be mapped as callbacks using the URIDispatcher class.
 *
 */
class ProjectRest extends BaseRest{

	private $projectMapper;

	private $userMapper;

	public function __construct(){
		parent::__construct();

		$this->userMapper = new UserMapper();
		$this->projectMapper = new ProjectMapper();
	}

	public function getProjects(){
		$currentUser = parent::authenticateUser();
		$projectsWithoutTasks = $this->projectMapper->findAll($currentUser->getUserMail());
		$projects = array();
		foreach($projectsWithoutTasks as $project){
			$t = $this->retrieveProject($project->getId(),$currentUser);
			array_push($projects, $this->encodeProject($t));
		}
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($projects));
	}

	public function getProject($projectId){
		$project = $this->retrieveProject($projectId);
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($this->encodeProject($project)));
	}

	public function createProject($data){
		$currentUser = parent::authenticateUser();
		$project = new Project();

		if (isset($data->name) && isset($data->users)) {
			$project = $this->loadProject($project, $data);
		}
		try {
			// validate Post object
			$project->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Project object into the database
			$projectId = $this->projectMapper->save($project);

			// response OK. Also send project in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$projectId);
			header('Content-Type: application/json');
			$encoded_project = $this->encodeProject($project);
			echo(json_encode($encoded_project));
		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo (json_encode($e->getErrors()));
		}
	}

	public function updateProject($projectId, $data){

		$project = $this->retrieveProject($projectId);
		$project = $this->loadProject($project, $data);
		try {			
			// validate Project object
			$project->checkIsValidForUpdate(); // if it fails, ValidationException
			// update the Project object in the database
			$this->projectMapper->update($project);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo (json_encode($e->getErrors()));
		}
	}

	public function deleteProject($projectId)
	{
		$project = $this->retrieveProject($projectId);
		$this->projectMapper->delete($project);
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	private function retrieveProject($projectId,$currentUser = NULL){
		if($currentUser == NULL) $currentUser = parent::authenticateUser();

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

	private function loadProject($project, $data): Project{

		$users = $this->userMapper->findAll();
		$project->setName($data->name);
		$projectUsers = array();
		$userNum = 1;
		foreach ($users as $user) {
			if (isset($data->users["user".$userNum])) {
				array_push($projectUsers, $user);
			}
			$userNum++;
		}
		$project->setUsers($projectUsers);
		
		return $project;
	}

	private function encodeProject($project){
		$encodedTasks = array();
		foreach ($project->getTasks() as $task) {
			$taskUsers = array();
			foreach ($task->getUsers() as $user) {
				array_push($taskUsers,array( 
					"username"=>$user->getUsername(),
					"user_mail"=>$user->getUserMail(),
					"passwd"=>$user->getPasswd()
				));
			}
			array_push($encodedTasks, array(
				"id"=>$task->getId(),
				"name"=>$task->getName(),
				"desc"=>$task->getDesc(),
				"projectID"=>$task->getProject(),
				"priority"=>$task->getPriority(),
				"beginDate"=>$task->getBeginDate(),
				"endDate"=>$task->getEndDate(),
				"status"=>$task->getStatus(),
				"users"=> $taskUsers
			));
		}
		$encodedUsers = array();
		foreach ($project->getUsers() as $user) {
			array_push($encodedUsers,array(
				"username"=>$user->getUsername(),
				"user_mail"=>$user->getUserMail(),
				"passwd"=>$user->getPasswd()
			));
		}

		$encoded = array(
			"id"=>$project->getId(),
			"name"=>$project->getName(),
			"users"=> $encodedUsers,
			"tasks"=> $encodedTasks
		);

		return $encoded;
	}

}

// URI-MAPPING for this Rest endpoint
$projectRest = new ProjectRest();
URIDispatcher::getInstance()
	->map("GET", "/projects", array($projectRest, "getProjects"))
	->map("GET", "/projects/$1", array($projectRest, "getProject"))
	->map("POST", "/projects", array($projectRest, "createProject"))
	->map("PUT", "/projects/$1", array($projectRest, "updateProject"))
	->map("DELETE", "/projects/$1", array($projectRest, "deleteProject"));