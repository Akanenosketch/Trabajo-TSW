<?php
//file: /controller/TasksController.php

require_once(__DIR__ . "/../model/User.php");
require_once(__DIR__ . "/../model/Project.php");
require_once(__DIR__ . "/../model/Task.php");

require_once(__DIR__ . "/../model/ProjectMapper.php");
require_once(__DIR__ . "/../model/TaskMapper.php");

require_once(__DIR__ . "/../controller/BaseController.php");

/**
 * Class TasksController
 *
 * Controller for tasks related use cases.
 *
 */
class TaskController extends BaseController
{

	/**
	 * Reference to the CommentMapper to interact
	 * with the database
	 *
	 * @var TaskMapper
	 */
	private $taskMapper;

	/**
	 * Reference to the PostMapper to interact
	 * with the database
	 *
	 * @var ProjectMapper
	 */
	private $projectMapper;

	public function __construct()
	{
		parent::__construct();

		$this->taskMapper = new TaskMapper();
		$this->projectMapper = new ProjectMapper();
	}




	public function view()
	{
	}



	/**
	 * Action to adds a task to a project
	 *
	 * When called via GET, it shows the add form
	 * When called via POST, it adds the task to the project
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP POST)</li>
	 * <li>title: title of the task (via HTTP POST)</li>
	 * <li>description: description of the task (via HTTP POST)</li>
	 * <li>status: status of the task (via HTTP POST)</li>
	 * <li>users: users assigned to the task (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>projects/view?id=project_id: If task was successfully added.
	 * <li>tasks/form: If this action is reached via HTTP GET (via include)</li>
	 * <li>tasks/form: If validation fails (via include). Includes these view variables:</li>
	 * <ul>
	 *	<li>errors: Array including per-field validation errors</li>
	 * </ul>
	 *
	 * @throws Exception if no user is in session
	 * @return void
	 */
	public function add()
	{
	}


	/**
	 * Action to edit a task
	 *
	 * When called via GET, it shows an edit form
	 * including the current data of the Task.
	 * When called via POST, it modifies the task in the
	 * database.
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP POST)</li>
	 * <li>task_id: Id of the task (via HTTP POST)</li>
	 * <li>title: title of the task (via HTTP POST)</li>
	 * <li>description: description of the task (via HTTP POST)</li>
	 * <li>status: status of the task (via HTTP POST)</li>
	 * <li>users: users assigned to the task (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>tasks/form: If this action is reached via HTTP GET (via include)</li>
	 * <li>projects/view?id=project_id: If task was successfully added.
	 * <li>tasks/form: If validation fails (via include). Includes these view variables:</li>
	 * <ul>
	 *	<li>tasks: The current Project instance, empty or being added (but not validated)</li>
	 *	<li>errors: Array including per-field validation errors</li>
	 * </ul>
	 * </ul>
	 * @throws Exception if no id was provided
	 * @throws Exception if no user is in session
	 * @throws Exception if there is not any task or project with the provided id
	 * @throws Exception if the current logged user is not assigned to the project
	 * @return void
	 */
	public function edit()
	{
	}


	/**
	 * Action to delete a task
	 *
	 * This action should only be called via HTTP POST
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP POST)</li>
	 * <li>task_id: Id of the task (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>projects/view?id=project_id: If task was successfully added.
	 * </ul>
	 * @throws Exception if no id was provided
	 * @throws Exception if no user is in session
	 * @throws Exception if there is not any project with the provided id
	 * @throws Exception if the current logged user is not assigned to the project
	 * @return void
	 */
	public function delete()
	{

		if (!isset($_POST["id"])) {
			throw new Exception("No project id given");
		}
		if (!isset($_POST["task_id"])) {
			throw new Exception("No task id given");
		}
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Deleting tasks requires login");
		}

		// Get the project object from the database
		$projectid = $_REQUEST["id"];
		$project = $this->projectMapper->findByIdWithAll($projectid);


		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: " . $projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exits int the project");
		}

		//Check if the task exists
				$taskid = $_REQUEST["task_id"];

		$task = null;
		foreach ($project->getTasks() as $t) {
				if(strcmp($t->getId(), $taskid) == 0){
					$task = $t;
				}
		}

		if ($task == NULL) {
			throw new Exception("no such task with id: " . $taskid);

		}

		// Delete the task object from the database
		$this->taskMapper->delete($taskid);

		// POST-REDIRECT-GET
		// perform the redirection. More or less:
		// header("Location: index.php?controller=projects&action=view&id=project_id")
		// die();
		$this->view->redirect("projects", "index","id=".$projectid);
	}





	/*	public function add() {
			if (!isset($this->currentUser)) {
				throw new Exception("Not in session. Adding tasks requires login");
			}

			if (isset($_POST["id"])) { // reaching via HTTP Post...

				// Get the Post object from the database
				$postid = $_POST["id"];
				$post = $this->postmapper->findById($postid);

				// Does the post exist?
				if ($post == NULL) {
					throw new Exception("no such post with id: ".$postid);
				}

				// Create and populate the Comment object
				$comment = new Comment();
				$comment->setContent($_POST["content"]);
				$comment->setAuthor($this->currentUser);
				$comment->setPost($post);

				try {

					// validate Comment object
					$comment->checkIsValidForCreate(); // if it fails, ValidationException

					// save the Comment object into the database
					$this->commentmapper->save($comment);

					// POST-REDIRECT-GET
					// Everything OK, we will redirect the user to the list of posts
					// We want to see a message after redirection, so we establish
					// a "flash" message (which is simply a Session variable) to be
					// get in the view after redirection.
					$this->view->setFlash("Comment \"".$post ->getTitle()."\" successfully added.");

					// perform the redirection. More or less:
					// header("Location: index.php?controller=posts&action=view&id=$postid")
					// die();
					$this->view->redirect("posts", "view", "id=".$post->getId());
				}catch(ValidationException $ex) {
					$errors = $ex->getErrors();

					// Go back to the form to show errors.
					// However, the form is not in a single page (comments/add)
					// It is in the View Post page.
					// We will save errors as a "flash" variable (third parameter true)
					// and redirect the user to the referring page
					// (the View post page)
					$this->view->setVariable("comment", $comment, true);
					$this->view->setVariable("errors", $errors, true);

					$this->view->redirect("posts", "view", "id=".$post->getId());
				}
			} else {
				throw new Exception("No such post id");
			}
		}
	}

	*/

	//add
//edit
//delete
//ver una tarea (para la descripcion)

	//view - form cargado y readonly
//add y edit = get levantan la form, post realiza
//delete - solo deletea con post


	// projects/index lista
// tasks/view?id=task_id   view?
// tasks/add
// tasks/add?id=tasks_id para el edit y view?

	//todos devuelven al mismo view del dashboard del projecto

	/*
	modalTaskName
	modalTaskDesc
	modalTaskStatus es un select
	modalTaskAssignees es un checkbox

	hay que hacer get al user a partir del email y meterlo al task, pero se guarda el proyect id*/


	//ACCIONES DEFINITIVAS
// add edit delete view
//solo hay 1 vista = form, estilo IU 
// add/edit con get levantan la form, post la ejecutan
//para cambiar el tipo desde la tabla hace un edit encubierto
//delete es n boton que borra y ya
//view levanta la form cubierta readonly
}
?>