<?php
// file: /core/ViewManager.php

/**
* Class ViewManager
*
* This class implements the glue between the controller
* and the view.
*
* This class is a singleton. You should use getInstance()
* to get the view manager instance.
*
*
*/
class ViewManager {

	/**
	* Values of view variables
	*
	* @var mixed
	*/
	private $variables = array();

	private function __construct() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		ob_start();
	}

	/// VARIABLES MANAGEMENT

	/**
	* Establishes a variable for the view
	*
	* Variables could be also kept in session (via $flash parameter)
	*
	* @param string $varname The name of the variable
	* @param any $value The value of the variable
	* @param boolean $flash If the variable value shoud be kept
	* in session
	*/
	public function setVariable($varname, $value, $flash=false) {
		$this->variables[$varname] = $value;
		if ($flash==true) {
			//a flash variable, will be stored in session_start
			if(!isset($_SESSION["viewmanager__flasharray__"])) {
				$_SESSION["viewmanager__flasharray__"][ $varname]=$value;
				print_r($_SESSION["viewmanager__flasharray__"]);
			}else{
				$_SESSION["viewmanager__flasharray__"][$varname]=$value;
			}
		}
	}

	/**
	* Retrieves a previously established variable.
	*
	* If the variable is a flash variable, it removes it
	* from the session after being retrieved
	*
	* @param string $varname The name of the variable
	* @param $default The value of the variable to return
	* if the variable does not exists
	* @return any value of the variable
	*/
	public function getVariable($varname, $default=NULL) {
		if (!isset($this->variables[$varname])) {
			if (isset($_SESSION["viewmanager__flasharray__"])
			&& isset($_SESSION["viewmanager__flasharray__"][$varname])){
				$toret=$_SESSION["viewmanager__flasharray__"][$varname];
				unset($_SESSION["viewmanager__flasharray__"][$varname]);
				return $toret;
			}
			return $default;
		}
		return $this->variables[$varname];
	}

	/**
	* Establishes a flash message
	*
	* Flash messages are useful to pass text from one page to other
	* via HTTP redirects, sinde they are kept in session.
	*
	* @param string $flashMessage The message to save into session
	* @return void
	*/
	public function setFlash($flashMessage) {
		$this->setVariable("__flashmessage__", $flashMessage, true);

	}

	/**
	* Retrieves the flash message (and pops it)
	*
	* @return string The flash message
	*/
	public function popFlash() {
		return $this->getVariable("__flashmessage__", "");
	}


	/// RENDERING

	/**
	* Renders an specified view of a specified controller
	*
	* If the $controller=mycontroller and $view=myview, the
	* selected php file will be: view/mycontroller/myview.php
	*
	* @param string $controller Name of the controller (in URL format
	* e.g: "posts")
	* @param string $viewname Name of the view
	* @return void
	*/
	public function render($controller, $viewname) {
		include(__DIR__."/../view/$controller/$viewname.php");	
		ob_flush();
	}

	/**
	* Sends an HTTP 302 redirection to a given action
	* inside a controller
	*
	* @param string $controller The name of the controller
	* @param string $action The name of the action
	* @param string $queryString An optional query string
	* @return void
	*/
	public function redirect($controller, $action, $queryString=NULL) {
		header("Location: index.php?controller=$controller&action=$action".(isset($queryString)?"&$queryString":""));
		die();
	}

	/**
	* Sends an HTTP 302 redirection to the refererring page, which
	* is the page where the user was, just before making the current
	* request.
	*
	* @param string $queryString An optional query string
	* @return void
	*/
	public function redirectToReferer($queryString=NULL) {
		header("Location: ".$_SERVER["HTTP_REFERER"].(isset($queryString)?"&$queryString":""));
		die();
	}

	// singleton
	private static $viewmanager_singleton = NULL;
	public static function getInstance() {
		if (self::$viewmanager_singleton == null) {
			self::$viewmanager_singleton = new ViewManager();
		}
		return self::$viewmanager_singleton;
	}

}


// force the first instantiation of the ViewManager
// since the buffered output will be needed including
// those cases where neither the controller nor the view get the instance of the viewmanager
ViewManager::getInstance();
