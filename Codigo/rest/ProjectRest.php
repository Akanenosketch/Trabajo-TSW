<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/ProjectMapper.php");

require_once(__DIR__."/../model/Task.php");
require_once(__DIR__."/../model/TaskMapper.php");

require_once(__DIR__."/BaseRest.php");

/**
* Class ProjectRest
*
* It contains operations for CRUDL projects, as well as to CUD tasks to projects.
*
* Methods gives responses following Restful standards. Methods of this class
* are intended to be mapped as callbacks using the URIDispatcher class.
*
*/
class ProjectRest extends BaseRest {
	private $projectMapper;
	private $taskMapper;
	private $userMapper;

	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();
		$this->projectMapper = new ProjectMapper();
		$this->taskMapper = new TaskMapper();
	}

	public function getProjects() {
		/*
		$posts = $this->postMapper->findAll();

		// json_encode Post objects.
		// since Post objects have private fields, the PHP json_encode will not
		// encode them, so we will create an intermediate array using getters and
		// encode it finally
		$posts_array = array();
		foreach($posts as $post) {
			array_push($posts_array, array(
				"id" => $post->getId(),
				"title" => $post->getTitle(),
				"content" => $post->getContent(),
				"author_id" => $post->getAuthor()->getusername()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($posts_array));*/
	}

	//devuelve los proyectos del usuario X
	public function getProjects($usermail) {
		/*
		$posts = $this->postMapper->findAll();

		// json_encode Post objects.
		// since Post objects have private fields, the PHP json_encode will not
		// encode them, so we will create an intermediate array using getters and
		// encode it finally
		$posts_array = array();
		foreach($posts as $post) {
			array_push($posts_array, array(
				"id" => $post->getId(),
				"title" => $post->getTitle(),
				"content" => $post->getContent(),
				"author_id" => $post->getAuthor()->getusername()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($posts_array));*/
	}

	public function createProject($data) {
		$currentUser = parent::authenticateUser();
		$project = new Project();

		if (isset($data->name) && isset($data->users)) {
			$project->setName($data->name);
			$projectUsers = array();

			$users = $this->userMapper->findAll();
			$userNum = 1;
			foreach ($users as $user) {
				if (isset( $data->users["user".$userNum])) {
					array_push($projectUsers, $user);
				}
				$userNum++;
			}
			$project->setUsers($projectUsers);
		}
		try {
			// validate Post object
			$project->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Project object into the database
			$projectId = $this->projectMapper->save($project);

			// response OK. Also send project in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$postId);
			header('Content-Type: application/json');
/*
			echo(json_encode(array(
				"id"=>$postId,
				"title"=>$post->getTitle(),
				"content" => $post->getContent()
			)));

			en el de crear task no lo envia?
			como mostrar los arrays de tareas y de users?
*/
		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function getProject($projectId) {
/*
if (!isset($_GET["id"])) {
			throw new Exception("A project id is mandatory");
		}

		if (!isset($this->currentUser)) {
			// Es posible quitarse permisos de un proyecto al editarlo e intentar verlo de nuevo
			throw new Exception("Not in session. Viewing projects requires login");
		}
		// Get the Project object from the database
		$projectid = $_GET["id"];
		$project = $this->projectMapper->findByIdWithAll($projectid);

		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: ".$projectid);
		}
		$users = $project->getUsers();

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $users)) {
			// Es posible quitarse permisos de un proyecto al editarlo e intentar verlo de nuevo
			$this->view->redirect( "projects", "index");
		}else{


		$this->view->setVariable("project", $project);
		// render the view (/view/projects/form.php)
		$this->view->render("projects", "view");

*/

		/*
		// find the Post object in the database
		$post = $this->postMapper->findByIdWithComments($postId);
		if ($post == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}

		$post_array = array(
			"id" => $post->getId(),
			"title" => $post->getTitle(),
			"content" => $post->getContent(),
			"author_id" => $post->getAuthor()->getusername()

		);

		//add comments
		$post_array["comments"] = array();
		foreach ($post->getComments() as $comment) {
			array_push($post_array["comments"], array(
				"id" => $comment->getId(),
				"content" => $comment->getContent(),
				"author" => $comment->getAuthor()->getusername()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($post_array));*/
	}

	public function updateProject($projectId, $data) {

		$currentUser = parent::authenticateUser();

		$project = $this->projectMapper->findByIdWithAll($projectId); 

		if ($project == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Project with id ".$projectId." not found");
			return;
		}
		//Comprobar si el current pertenece al proyecto
		if (!in_array($currentUser, $project->getUsers())) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not registered to this project");
			return;
		}
		$users = $this->userMapper->findAll();

		try {
			
			$project->setName($data->name);
			$projectUsers = array();
			$userNum = 1;
			foreach ($users as $user) {
				if (isset( $data->users["user".$userNum])) {
					array_push($projectUsers, $user);
				}
				$userNum++;
			}
			$project->setUsers($projectUsers);

			// validate Project object
			$project->checkIsValidForUpdate(); // if it fails, ValidationException
			// update the Project object in the database
			$this->projectMapper->update($project);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function deleteProject($projectId) {
		$currentUser = parent::authenticateUser(); 
		$project = $this->projectMapper->findById($projectId); 

		if ($project == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Project with id ".$projectId." not found");
			return;
		}
		//Comprobar si el current pertenece al proyecto
		if (!in_array($currentUser, $project->getUsers())) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not registered to this project");
			return;
			}

		$this->projectMapper->delete($project);
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	public function createTask($projectId, $data) {
		$currentUser = parent::authenticateUser();
		
		// Get the Project object from the database
		$project = $this->projectMapper->findByIdWithAll($projectid);
		if ($project == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Project with id ".$projectId." not found");
			return;
		}
		//Comprobar si el current pertenece al proyecto
		if (!in_array($currentUser, $project->getUsers())) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not registered to this project");
			return;
		}

		// Create and populate the Task object
		$task = new Task();

		$task->setProject($projectId);
		$task->setName($data->name);
		$task->setStatus($data->status);
		$task->setDesc($data->desc);

		$users = array();
		$userNum = 1;
		foreach ($project->getUsers() as $user) {
			if (isset( $data->users["user".$userNum])) {
				array_push($users, $user);
			}	
			$userNum++;
		}
		$task->setUsers($users);

		try {
			$task->checkIsValidForCreate(); // if it fails, ValidationException
			$this->taskMapper->save($task);
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
		}catch(ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function updateTask($projectId, $taskId, $data){
		$currentUser = parent::authenticateUser();

		$project = $this->projectMapper->findByIdWithAll($projectId); 

		if ($project == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Project with id ".$projectId." not found");
			return;
		}
		//Comprobar si el current pertenece al proyecto
		if (!in_array($currentUser, $project->getUsers())) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not registered to this project");
			return;
		}

		$task = null;
		foreach ($project->getTasks() as $t) {
			if (strcmp($t->getId(), $taskid) == 0) {
				$task = $t;
			}
		}

		if ($task == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Task with id ".$taskid." not found");
			return;
		}
	
		try {
			$task->setProject($projectid);
			$task->setName($data->name);
			$task->setStatus($data->status);
			$task->setDesc($data->desc);

			$users = array();
			$userNum = 1;
			
			foreach ($project->getUsers() as $user) {
				if (isset( $data->users["user".$userNum])) {
					array_push($users, $user);
				}	
				$userNum++;
			}
			$task->setUsers($users);

			// validate Task object	
			$task->checkIsValidForUpdate(); // if it fails, ValidationException
			//Update Task object in the database
			$this->taskMapper->update($task);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function deleteTask($projectId, $taskId){
		$currentUser = parent::authenticateUser(); 
		$project = $this->projectMapper->findByIdWithAll($projectId); 

		if ($project == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Project with id ".$projectId." not found");
			return;
		}
		//Comprobar si el current pertenece al proyecto
		if (!in_array($currentUser, $project->getUsers())) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not registered to this project");
			return;
		}

		$task = null;
		foreach ($project->getTasks() as $t) {
			if (strcmp($t->getId(), $taskid) == 0) {
				$task = $t;
			}
		}

		if ($task == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Task with id ".$taskid." not found");
			return;
		}

		$this->taskMapper->delete($task);
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

}

// URI-MAPPING for this Rest endpoint
$projectRest = new ProjectRest();
URIDispatcher::getInstance()
->map("GET",	"/projects", array($projectRest,"getProjects"))
->map("GET",	"/projects/users/$1", array($projectRest,"getProjects"))
->map("GET",  	"/projects/$1", array($projectRest,"getProject"))
->map("POST", 	"/projects", array($projectRest,"createProject"))
->map("PUT",	"/projects/$1", array($projectRest,"updateProject"))
->map("DELETE", "/projects/$1", array($projectRest,"deleteProject"))
->map("POST", 	"/projects/$1/tasks", array($projectRest,"createTask"))
->map("PUT",	"/projects/$1/tasks/$2", array($projectRest,"updateTask"))
->map("DELETE", "/projects/$1/tasks/$2", array($projectRest,"deleteTask"));


//Esto es un CRUDL de projectos + L de projectos de X usuario
// Tiene CUD de Task

//Aparte de esto estaria bien ampliaciones 