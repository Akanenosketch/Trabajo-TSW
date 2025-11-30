<?php
//file: /controller/TasksController.php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/Task.php");

require_once(__DIR__."/../model/ProjectMapper.php");
require_once(__DIR__."/../model/TaskMapper.php");

require_once(__DIR__."/../controller/BaseController.php");

/**
 * Class TasksController
 *
 * Controller for tasks related use cases.
 *
 */
class TaskController extends BaseController
{

	/**
	 * Reference to the TaskMapper to interact
	 * with the database
	 *
	 * @var TaskMapper
	 */
	private $taskMapper;

	/**
	 * Reference to the ProjectMapper to interact
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

	/**
	 * Action to view a given task.
	 *
	 * This action should only be called via GET
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP POST)</li>
	 * <li>task_id: Id of the task (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>tasks/form: If post is successfully loaded (via include).	Includes these view variables:</li>
	 * <ul>
	 *	<li>task: The current Task retrieved</li>
	 * </ul>
	 * </ul>
	 * @return void
	 *
	 */
	public function view()
	{
		if (!isset($_REQUEST["id"])) {
			throw new Exception("A project id is mandatory");
		}

		if (!isset($_REQUEST["task_id"])) {
			throw new Exception("A task id is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Viewing tasks requires login");
		}

		// Get the Project object from the database
		$projectid = $_REQUEST["id"];
		$project = $this->projectMapper->findByIdWithAll($projectid);

		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: ".$projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exist in the project");
		}

		// Get the task object
		$taskid = $_REQUEST["task_id"];
		$task = NULL;

		foreach ($project->getTasks() as $t) {
			if ($t->getId() == $taskid) {
				$task = $t;
			}
		}

		if ($task == NULL) {
			throw new Exception("no such task with id: ".$taskid);
		}

		// Check if the user is assigned to task
		if (!in_array($this->currentUser, $task->getUsers())) {
			throw new Exception("logged user does not exists in the task");
		}
		$this->view->setVariable("task", $task);
		$this->view->setVariable("isViewing", true);
		// render the view (/view/tasks/form.php)
		$this->view->render("tasks", "form");
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
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Adding tasks requires login");
		}
		if (!isset($_REQUEST["id"])) {
			throw new Exception("A project id is mandatory");
		}
		// Get the Project object from the database
		$projectid = $_REQUEST["id"];
		$project = $this->projectMapper->findByIdWithAll($projectid);

		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: ".$projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exist in the project");
		}

		if (isset($_POST["id"])) { // reaching via HTTP Post...

			// Create and populate the Task object
			$task = new Task();

			$task->setProject($projectid);
			$task->setName($_POST["title"]);
			$task->setStatus($_POST["status"]);
			$task->setDesc($_POST["description"]);

			$users = array();
			foreach ($project->getUsers() as $user) {
				if (isset($_POST[$user->getUserMail()])) {
					array_push($users, $user);
				}
			}
			$task->setUsers($users);
			try {
				// validate Task object
				$task->checkIsValidForCreate(); // if it fails, ValidationException

				// save the Comment object into the database
				$this->taskMapper->save($task);

				// POST-REDIRECT-GET projects/view?id=project_id
				$this->view->redirect("projects", "view", "id=".$projectid);
			} catch (ValidationException $ex) {
				$errors = $ex->getErrors();

				// Go back to the form to show errors.
				$this->view->setVariable("errors", $errors);
				$this->view->setVariable("users", $project->getUsers());
				$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());
				$this->view->setVariable("projectID", $_REQUEST["id"]);
				$this->view->redirect("tasks", "form");
			}
		} else {
			// render the view (/view/tasks/form.php)
			$this->view->setVariable("users", $project->getUsers());
			$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());
			$this->view->setVariable("projectID", $_REQUEST["id"]);
			$this->view->render("tasks", "form");
		}
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
		if (!isset($_REQUEST["id"])) {
			throw new Exception("A project id is mandatory");
		}

		if (!isset($_REQUEST["task_id"])) {
			throw new Exception("A task id is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing tasks requires login");
		}

		// Get the Project object from the database
		$projectid = $_REQUEST["id"];
		$project = $this->projectMapper->findByIdWithAll($projectid);

		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: ".$projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exist in the project");
		}

		// Get the task object
		$taskid = $_REQUEST["task_id"];
		$task = NULL;

		foreach ($project->getTasks() as $t) {
			if ($t->getId() == $taskid) {
				$task = $t;
			}
		}

		if ($task == NULL) {
			throw new Exception("no such task with id: ".$taskid);
		}

		// Check if the user is assigned to task
		if (!in_array($this->currentUser, $task->getUsers())) {
			throw new Exception("logged user does not exists in the task");
		}

		if (isset($_POST["id"])) { // reaching via HTTP Post...

			try {
				$task->setProject($projectid);
				$task->setName($_POST["title"]);
				$task->setStatus($_POST["status"]);
				$task->setDesc($_POST["description"]);

				$users = array();
				foreach ($project->getUsers() as $user) {
					if (isset($_POST[$user->getUserMail()])) {
						array_push($users, $user);
					}
				}
				$task->setUsers($users);


				// validate Task object
				$task->checkIsValidForUpdate(); // if it fails, ValidationException
				// update the Task object in the database
				$this->taskMapper->update($task);

				// POST-REDIRECT-GET
				$this->view->redirect("projects", "view", "id=".$projectid);

			} catch (ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
				$this->view->setVariable("task", $task);
				$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());
				$this->view->setVariable("users", $project->getUsers());
				$this->view->redirect("tasks", "form");
			}
		} else {
			$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());
			$this->view->setVariable("task", $task);
			$this->view->setVariable("users", $project->getUsers());
			// render the view (/view/tasks/form.php)
			$this->view->render("tasks", "form");
		}
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
	 * <li>projects/view?id=project_id: If task was successfully deleted.
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
			throw new Exception("no such project with id: ".$projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exist in the project");
		}

		//Check if the task exists
		$taskid = $_REQUEST["task_id"];

		$task = null;
		foreach ($project->getTasks() as $t) {
			if (strcmp($t->getId(), $taskid) == 0) {
				$task = $t;
			}
		}

		if ($task == NULL) {
			throw new Exception("no such task with id: ".$taskid);

		}

		// Delete the task object from the database
		$this->taskMapper->delete($taskid);

		// POST-REDIRECT-GET
		// perform the redirection. More or less:
		// header("Location: index.php?controller=projects&action=view&id=project_id")
		// die();
		$this->view->redirect("projects", "index", "id=".$projectid);
	}

}
?>