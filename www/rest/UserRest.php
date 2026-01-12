<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");
require_once(__DIR__."/BaseRest.php");

/**
 * Class UserRest
 *
 * It contains operations for adding and checking users credentials.
 * Methods gives responses following Restful standards. Methods of this class
 * are intended to be mapped as callbacks using the URIDispatcher class.
 *
 */
class UserRest extends BaseRest{
	private $userMapper;

	public function __construct(){
		parent::__construct();
		$this->userMapper = new UserMapper();
	}


	public function list(){
		$currentLogged = parent::authenticateUser();

		$mails = array();
		$users = $this->userMapper->findAll();
		foreach($users as $user){
			array_push($mails, $user->getUserMail());
		}
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($mails));
	}

	public function register($data){
		$user = new User();
		if (isset($data->username) && isset($data->user_mail) && isset($data->passwd)) {
			$user = new User($data->username, $data->user_mail, $data->passwd);
		}
		try {
			$user->checkIsValidForRegister();
			if (!$this->userMapper->usermailExists($data->user_mail)) {
				$this->userMapper->save($user);
			} else {
				$errors = array();
				$errors["user_mail"] = i18n("Existe un usuario con el mismo correo");
				throw new ValidationException($errors, i18n("usuario no valido"));
			}
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header("Location: ".$_SERVER['REQUEST_URI']."/".$data->username);
		} catch (ValidationException $e) {
			http_response_code(response_code: 400);
			header('Content-Type: application/json');
			echo (json_encode($e->getErrors()));
		}
	}

	public function edit($usermail,$data){
		$currentLogged = parent::authenticateUser();
		try {
		if (isset($data->username) && isset($data->user_mail) && isset($data->passwd)) {
		$user = new User($data->username, $usermail, $data->passwd);
		} else throw new ValidationException(array());
		
			if(strcmp($usermail, $currentLogged->getUserMail()) != 0){
			
				header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
				echo ("You are not authorized to edit anyone but you");
				return;
			}
			$user->checkIsValidForRegister();
			if ($this->userMapper->usermailExists($usermail)) {
				$this->userMapper->update($user);
			} else {
				$errors = array();
				$errors["user_mail"] = i18n("No existe un usuario con el mismo correo");
				throw new ValidationException($errors, i18n("usuario no valido"));
			}
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo (json_encode($e->getErrors()));
		}
	}

	public function login($usermail){

		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
			header('WWW-Authenticate: Basic realm="Rest API of MVCBLOG"');
			die('This operation requires authentication');
		} else {
			if (
				$this->userMapper->isValidUser(
					$_SERVER['PHP_AUTH_USER'],
					$_SERVER['PHP_AUTH_PW']
				)
			) {
				header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
				$user = $this->userMapper->findByEmail($_SERVER['PHP_AUTH_USER']);

				$encodedUser = array(
					"username" => $user->getUsername(),
					"user_mail" => $user->getUserMail(),
					"passwd" => $user->getPasswd()
				);

				echo (json_encode($encodedUser));
			} else {
				$errors = array();
				$errors["user_mail"] = i18n("usuario no valido");
				http_response_code(response_code: 400);
				header('Content-Type: application/json');
				$e = new ValidationException($errors, i18n("usuario no valido"));
				echo (json_encode($e->getErrors()));
			}
		}
	}

}

// URI-MAPPING for this Rest endpoint
$userRest = new UserRest();
URIDispatcher::getInstance()
	->map("GET", "/users/$1", array($userRest, "login"))
	->map("GET", "/users", array($userRest, "list"))
	->map("PUT", "/users/$1", array($userRest, "edit"))
	->map("POST", "/users"."/", array($userRest, "register"));


//Esto es un CR de Users, estaria bien ampliarlo para tener CRUD