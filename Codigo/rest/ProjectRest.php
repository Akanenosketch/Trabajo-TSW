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

	public function __construct() {
		parent::__construct();

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

	public function createProject($data) {
		/*
		$currentUser = parent::authenticateUser();
		$post = new Post();

		if (isset($data->title) && isset($data->content)) {
			$post->setTitle($data->title);
			$post->setContent($data->content);

			$post->setAuthor($currentUser);
		}

		try {
			// validate Post object
			$post->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Post object into the database
			$postId = $this->postMapper->save($post);

			// response OK. Also send post in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$postId);
			header('Content-Type: application/json');
			echo(json_encode(array(
				"id"=>$postId,
				"title"=>$post->getTitle(),
				"content" => $post->getContent()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}*/
	}

	public function readProject($projectId) {
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

	public function updateProject($projectId, $data) {ç
		/*
		$currentUser = parent::authenticateUser();

		$post = $this->postMapper->findById($postId);
		if ($post == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}

		// Check if the Post author is the currentUser (in Session)
		if ($post->getAuthor() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the author of this post");
			return;
		}
		$post->setTitle($data->title);
		$post->setContent($data->content);

		try {
			// validate Post object
			$post->checkIsValidForUpdate(); // if it fails, ValidationException
			$this->postMapper->update($post);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}*/
	}


	//traducir los echos?


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
	/*	$currentUser = parent::authenticateUser();

		$post = $this->postMapper->findById($postId);
		if ($post == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}

		$comment = new Comment();
		$comment->setContent($data->content);
		$comment->setAuthor($currentUser);
		$comment->setPost($post);

		try {
			$comment->checkIsValidForCreate(); // if it fails, ValidationException

			$this->commentMapper->save($comment);

			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');

		}catch(ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	*/}


	public function updateTask($projectId, $taskId, $data){
		//TODO
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
->map("GET",	"/project", array($projectRest,"getProjects"))
->map("GET",  	"/project/$1", array($projectRest,"readProject"))
->map("POST", 	"/project", array($projectRest,"createProject"))
->map("PUT",	"/project/$1", array($projectRest,"updateProject"))
->map("DELETE", "/project/$1", array($projectRest,"deleteProject"))
->map("POST", 	"/project/$1/task", array($projectRest,"createComment"))
->map("PUT",	"/project/$1/task/$2", array($projectRest,"updateTask"))
->map("DELETE", "/project/$1/task/$2", array($projectRest,"deleteTask"));
