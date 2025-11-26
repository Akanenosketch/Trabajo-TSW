<?php

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../core/I18n.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../controller/BaseController.php");

/**
* Class UsersController
*
* Controller to login, logout and user registration
*
*/
class UsersController extends BaseController {

	/**
	* Reference to the UserMapper to interact
	* with the database
	*
	* @var UserMapper
	*/
	private $userMapper;

	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();
	}

	/**
	* Action to show the welcome page.
	*
	* No HTTP parameters are needed.
	*
	* The views are:
	* <ul>
	* <li>users/welcomePage (via include)</li>
	* </ul>
	*/
	public function index() {

		// render the view (/view/users/welcomePage.php)
		$this->view->render("users", "welcomePage");
	}

	/**
	* Action to logout
	*
	* This action should be called via GET
	*
	* No HTTP parameters are needed.
	*
	* The views are:
	* <ul>
	* <li>users/welcomePage (via redirect)</li>
	* </ul>
	*
	* @return void
	*/
	public function logout() {
		session_destroy();

		// perform a redirection. More or less:
		// header("Location: index.php?controller=users&action=login")
		// die();
		$this->view->redirect("users", "index");
	}


	/**
	* Action to login
	*
	* Logins a user checking its creedentials agains
	* the database
	*
	* When called via GET, it shows the login form
	* When called via POST, it tries to login
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>correo: The mailUsuario (via HTTP POST)</li>
	* <li>contrasena: The password (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>users/login: If this action is reached via HTTP GET (via include)</li>
	* <li>projects/index: If login succeds (via redirect)</li>
	* <li>users/login: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>errors: Array including validation errors</li>
	* </ul>
	* </ul>
	*
	* @return void
	*/
	public function login() {
		if (isset($_POST["correo"])){ // reaching via HTTP Post...
			//process login form
			if ($this->userMapper->isValidUser($_POST["correo"], $_POST["contrasena"])) {

				$_SESSION["currentuser"]= $this->userMapper->findByEmail($_POST["correo"])->getUsername();

				// send user to the restricted area (HTTP 302 code)
				$this->view->redirect("projects", "index");

			}else{
				$errors = array();
				$errors["general"] = "user is not valid";
				$this->view->setVariable("errors", $errors);
			}
		}

		// render the view (/view/users/login.php)
		$this->view->render("users", "login");
	}

	/**
	* Action to register
	*
	* When called via GET, it shows the register form.
	* When called via POST, it tries to add the user
	* to the database.
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>nombreUsuario: The nombreUsuario (via HTTP POST)</li>
	* <li>correo: The mailUsuario (via HTTP POST)</li>
	* <li>contrasena: The password (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>users/register: If this action is reached via HTTP GET (via include)</li>
	* <li>users/welcomePage: If register succeds (via redirect)</li>
	* <li>users/register: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>errors: Array including validation errors</li>
	* </ul>
	* </ul>
	*
	* @return void
	*/
	public function register() {

		$user = new User();

		if (isset($_POST["correo"])){ // reaching via HTTP Post...

			// populate the User object with data form the form
			$user->setUsername($_POST["nombreUsuario"]);
			$user->setUserMail($_POST["correo"]);
			$user->setPassword($_POST["contrasena"]);

			try{
				$user->checkIsValidForRegister(); // if it fails, ValidationException

				// check if user exists in the database
				if (!$this->userMapper->usermailExists($_POST["correo"])){

					// save the User object into the database
					$this->userMapper->save($user);

					// POST-REDIRECT-GET
					// Everything OK, we will redirect the user to the welcomePage
					
					// perform the redirection. More or less:
					// header("Location: index.php?controller=users&action=login")
					// die();
					$this->view->redirect("users", "index",'correo='.$_POST["correo"]);
				} else {
					$errors = array();
					$errors["user_mail"] = "Existe un usuario con el mismo correo";
					$this->view->setVariable("errors", $errors);
				}
			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the User object visible to the view
		$this->view->setVariable("user", $user);

		// render the view (/view/users/register.php)
		$this->view->render("users", "register");

	}





}
