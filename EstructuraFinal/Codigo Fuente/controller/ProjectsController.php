<?php
//file: controller/ProjectsController.php

require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/Task.php");
require_once(__DIR__."/../model/ProjectMapper.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
 * Class ProjectsController
 *
 * Controller to make a CRUDL of Project entities
 *
 */
class ProjectsController extends BaseController
{

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

		$this->projectMapper = new ProjectMapper();
		$this->userMapper = new UserMapper();
	}


	/**
	 * Action to list projects.
	 *
	 * Loads all the projects from the database.
	 *
	 * The views are:
	 * <ul>
	 * <li>projects/index (via include)</li>
	 * </ul>
	 * </ul>
	 */
	public function index()
	{

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Viewing projects requires login");
		}

		$user_mail = $this->currentUser->getUserMail();

		// obtain the data from the database
		$projectsWithoutTasks = $this->projectMapper->findAll($user_mail);

		$projects = array();
		foreach ($projectsWithoutTasks as $p) {
			$pWithTasks = $this->projectMapper->findByIdWithAll($p->getId());
			array_push($projects, $pWithTasks);
		}

		// put the array containing Post object to the view
		$this->view->setVariable("projects", $projects);
		$this->view->setVariable("currentusername", $this->currentUser->getUsername());
		$this->view->setVariable("currentusermail", $user_mail);

		// render the view (/view/projects/index.php)
		$this->view->render("projects", "index");

	}



	/**
	 * Action to view a given project.
	 *
	 * This action should only be called via GET
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP GET)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>project/view: If post is successfully loaded (via include).	Includes these view variables:</li>
	 * <ul>
	 *	<li>project: The current Project retrieved</li>
	 *	<li>tasks: The current Project tasks instance, empty or
	 *	being added</li>
	 * </ul>
	 * <li>projects/index: If project id does not exist (via include). Includes these view variables:</li>
	 * <ul>
	 * <li>errors: Array including validation errors</li>
	 * </ul>
	 * </ul>
	 * @return void
	 *
	 */
	public function view()
	{
		if (!isset($_GET["id"])) {
			throw new Exception("A project id is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Viewing projects requires login");
		}

		// Get the Project object from the database
		$projectid = $_GET["id"];
		$project = $this->projectMapper->findByIdWithAll($projectid);

		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: ".$projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exist in the project");
		}

		$this->view->setVariable("project", $project);
		// render the view (/view/projects/form.php)
		$this->view->render("projects", "view");
	}

	/**
	 * Action to add a new project
	 * When called via GET, it shows the add form
	 * When called via POST, it adds the project to the
	 * database
	 * 
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>name: Name of the project (via HTTP POST)</li>
	 * <li>users: emails of the users to be assigned to the project (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>projects/form: If this action is reached via HTTP GET (via include)</li>
	 * <li>projects/index: If post was successfully added (via redirect)</li>
	 * <li>projects/form: If validation fails (via include). Includes these view variables:</li>
	 * <ul>
	 *	<li>addProject: The current Project instance, empty or
	 *	being added (but not validated)</li>
	 *	<li>errors: Array including per-field validation errors</li>
	 * </ul>
	 * </ul>
	 * @throws Exception if no user is in session
	 * @return void
	 */
	public function add()
	{
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Adding projects requires login");
		}

		$users = $this->userMapper->findAll();

		if (isset($_POST["name"])) { // reaching via HTTP Post...
			// Create and populate the Project object
			$project = new Project();

			$project->setName($_POST["name"]);

			$projectUsers = array();
			$userNum = 1;
			foreach ($users as $user) {
				if (isset($_POST["user".$userNum])) {
					array_push($projectUsers, $user);
				}
				$userNum++;
			}
			$project->setUsers($projectUsers);

			try {
				// validate Project object
				$project->checkIsValidForCreate(); // if it fails, ValidationException

				// save the Project object into the database
				$this->projectMapper->save($project);

				// POST-REDIRECT-GET 
				$this->view->redirect("projects", "index");
			} catch (ValidationException $ex) {
				$errors = $ex->getErrors();

				// Go back to the form to show errors.
				$this->view->setVariable("errors", $errors);
			}
		}
	 	// render the view (/view/projects/form.php)
		$this->view->setVariable("users", $users);
		$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());
		$this->view->render("projects", "form");
	}

	/**
	 * Action to edit a project
	 *
	 * When called via GET, it shows an edit form
	 * including the current data of the Project.
	 * When called via POST, it modifies the project in the
	 * database.
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP POST and GET)</li>
	 * <li>name: Name of the project (via HTTP POST)</li>
	 * <li>users: emails of the users to be assigned to the project (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>projects/form: If this action is reached via HTTP GET (via include)</li>
	 * <li>projects/index: If project was successfully edited (via redirect)</li>
	 * <li>projects/form: If validation fails (via include). Includes these view variables:</li>
	 * <ul>
	 *	<li>project: The current Project instance, empty or being added (but not validated)</li>
	 *	<li>errors: Array including per-field validation errors</li>
	 * </ul>
	 * </ul>
	 * @throws Exception if no id was provided
	 * @throws Exception if no user is in session
	 * @throws Exception if there is not any project with the provided id
	 * @throws Exception if the current logged user is not assigned to the project
	 * @return void
	 */
	public function edit()
	{
		if (!isset($_REQUEST["id"])) {
			throw new Exception("A project id is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing projects requires login");
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

		$users = $this->userMapper->findAll();

		if (isset($_POST["id"])) { // reaching via HTTP Post...

			try {
				$project->setName($_POST["name"]);

				$projectUsers = array();
				foreach ($users as $user) {
					if (isset($_POST[$user->getUserMail()])) {
						array_push($projectUsers, $user);
					}
				}
				$project->setUsers($projectUsers);

				// validate Project object
				$project->checkIsValidForUpdate(); // if it fails, ValidationException
				// update the Project object in the database
				$this->projectMapper->update($project);

				// POST-REDIRECT-GET
				$this->view->redirect("projects", "view", "id=".$projectid);

			} catch (ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}
		$this->view->setVariable("project", $project);
		$this->view->setVariable("projectUsers", $project->getUsers());
		$this->view->setVariable("users", $users);
		$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());
		// render the view (/view/projects/form.php)
		$this->view->render("projects", "form");		
	}

	/**
	 * Action to delete a project
	 *
	 * This action should only be called via HTTP POST
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>id: Id of the project (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>projects/index: If project was successfully deleted (via redirect)</li>
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
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Deleting projects requires login");
		}

		// Get the project object from the database
		$projectid = $_POST["id"];
		$project = $this->projectMapper->findById($projectid);
		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: ".$projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exist in the project");
		}

		// Delete the project object from the database
		$this->projectMapper->delete($project);

		// POST-REDIRECT-GET
		// perform the redirection. More or less:
		// header("Location: index.php?controller=projects&action=index")
		// die();
		$this->view->redirect("projects", "index");
	}
}
?>