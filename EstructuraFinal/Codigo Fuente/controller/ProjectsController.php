<?php
//file: controller/ProjectsController.php

require_once(__DIR__."/../model/Project.php");
require_once(__DIR__."/../model/Task.php");
require_once(__DIR__."/../model/ProjectMapper.php");
require_once(__DIR__."/../model/User.php");

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
			throw new Exception("Not in session. Adding tasks requires login");
		}

		$user_mail = $this->currentUser->getUserMail();

		// obtain the data from the database
		$projectsWithoutTasks = $this->projectMapper->findAll($user_mail);

		$projects = array();
		foreach ($projectsWithoutTasks as $p) {
			$pWithTasks = $this->projectMapper->findByIdWithAll($p->getId());
			array_push($users, $pWithTasks);
		}

		// put the array containing Post object to the view
		$this->view->setVariable("projects", $projects);
		$this->view->setVariable("currentusername", $this->currentUser->getUsername());
		$this->view->setVariable("currentusermail", $this->currentUser->getUserMail());

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
	}

	/**
	 * Action to add a new project
	 * When called via GET, it shows the add form
	 * When called via POST, it adds the project to the
	 * database
	 * 
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>title: Title of the project (via HTTP POST)</li>
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
	 * <li>title: Title of the project (via HTTP POST)</li>
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
		$projectid = $_REQUEST["id"];
		$project = $this->projectMapper->findById($projectid);
		// Does the project exist?
		if ($project == NULL) {
			throw new Exception("no such project with id: " . $projectid);
		}

		// Check if the currentUser (in Session) is in the Project
		if (!in_array($this->currentUser, $project->getUsers())) {
			throw new Exception("logged user does not exits int the project");
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













			public function view(){
			if (!isset($_GET["id"])) {
				throw new Exception("id is mandatory");
			}

			$postid = $_GET["id"];

			// find the Post object in the database
			$post = $this->postMapper->findByIdWithComments($postid);

			if ($post == NULL) {
				throw new Exception("no such post with id: ".$postid);
			}

			// put the Post object to the view
			$this->view->setVariable("post", $post);

			// check if comment is already on the view (for example as flash variable)
			// if not, put an empty Comment for the view
			$comment = $this->view->getVariable("comment");
			$this->view->setVariable("comment", ($comment==NULL)?new Comment():$comment);

			// render the view (/view/posts/view.php)
			$this->view->render("posts", "view");

		}


	public function add()
	{
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Adding posts requires login");
		}

		$post = new Post();

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// populate the Post object with data form the form
			$post->setTitle($_POST["title"]);
			$post->setContent($_POST["content"]);

			// The user of the Post is the currentUser (user in session)
			$post->setAuthor($this->currentUser);

			try {
				// validate Post object
				$post->checkIsValidForCreate(); // if it fails, ValidationException

				// save the Post object into the database
				$this->postMapper->save($post);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of posts
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Post \"%s\" successfully added."), $post->getTitle()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("posts", "index");

			} catch (ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Post object visible to the view
		$this->view->setVariable("post", $post);

		// render the view (/view/posts/add.php)
		$this->view->render("posts", "add");

	}

	public function edit()
	{
		if (!isset($_REQUEST["id"])) {
			throw new Exception("A post id is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing posts requires login");
		}


		// Get the Post object from the database
		$postid = $_REQUEST["id"];
		$post = $this->postMapper->findById($postid);

		// Does the post exist?
		if ($post == NULL) {
			throw new Exception("no such post with id: " . $postid);
		}

		// Check if the Post author is the currentUser (in Session)
		if ($post->getAuthor() != $this->currentUser) {
			throw new Exception("logged user is not the author of the post id " . $postid);
		}

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// populate the Post object with data form the form
			$post->setTitle($_POST["title"]);
			$post->setContent($_POST["content"]);

			try {
				// validate Post object
				$post->checkIsValidForUpdate(); // if it fails, ValidationException

				// update the Post object in the database
				$this->postMapper->update($post);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of posts
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Post \"%s\" successfully updated."), $post->getTitle()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("posts", "index");

			} catch (ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Post object visible to the view
		$this->view->setVariable("post", $post);

		// render the view (/view/posts/add.php)
		$this->view->render("posts", "edit");
	}


*/
}
