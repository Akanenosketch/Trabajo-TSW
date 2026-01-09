<?php
//file: /controller/CategoriesController.php

require_once(__DIR__."/../model/Category.php");
require_once(__DIR__."/../model/CategoryMapper.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
 * Class CategoriesController
 *
 * Controller for Category related use cases.
 */
class CategoriesController extends BaseController{

	/**
	 * Reference to the CategoryMapper to interact
	 * with the database
	 *
	 * @var CategoryMapper
	 */
	private $categoryMapper;

	public function __construct(){
		parent::__construct();

		$this->categoryMapper = new CategoryMapper();
	}







    public function index(){

//        TODO
    }





	/**
	 * Action to view a given category.
	 *
	 * This action should only be called via GET
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>name: name of the category (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>category/form: If category is successfully loaded (via include).	Includes these view variables:</li>
	 * <ul>
	 *	<li>category: The current Category retrieved</li>
	 * </ul>
	 * </ul>
	 * @return void
	 *
	 */
	public function view(){

		$cat = $this->retrieveCategory();

		$this->view->setVariable("category", $cat);
		$this->view->setVariable("isViewing", true);
		// render the view (/view/tasks/form.php)
		$this->view->render("categories", "form");
	}

	/**
	 * Action to add a new category
	 * When called via GET, it shows the add form
	 * When called via POST, it adds the category to the
	 * database
	 * 
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>name: Name of the category (via HTTP POST)</li>
	 * <li>desc: desc of the category (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>category/form: If this action is reached via HTTP GET (via include)</li>
	 * <li>category/index: If post was successfully added (via redirect)</li>
	 * <li>category/form: If validation fails (via include). Includes these view variables:</li>
	 * <ul>
	 *	<li>errors: Array including per-field validation errors</li>
	 * </ul>
	 * </ul>
	 * @throws Exception if no user is in session
	 * @return void
	 */	
    public function add(){

        if (!isset($this->currentUser)) {
            throw new Exception("Not in session. Adding projects requires login");
        }


        if (isset($_POST["name"])) { // reaching via HTTP Post...
            // Create and populate the Project object
            $cat = new Category();
            $cat = $this->loadCategory($cat);

            try {
                // validate Project object
                $cat->checkIsValidForRegister(); // if it fails, ValidationException

                // save the Project object into the database
                $this->categoryMapper->save($cat);

                // POST-REDIRECT-GET 
                $this->view->redirect("categories", "index");
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
                // Go back to the form to show errors.
                $this->view->setVariable("errors", $errors);
            }
        }

        // render the view (/view/tasks/form.php)
        $this->view->render("categories", "form");

    }

	/**
	 * Action to edit a category
	 *
	 * When called via GET, it shows an edit form
	 * including the current data of the category.
	 * When called via POST, it modifies the category in the
	 * database.
	 *
	 * The expected HTTP parameters are:
	 * <ul>
	 * <li>name: Name of the category (via HTTP POST)</li>
	 * <li>des: desc of the category (via HTTP POST)</li>
	 * </ul>
	 *
	 * The views are:
	 * <ul>
	 * <li>category/form: If this action is reached via HTTP GET (via include)</li>
	 * <li>category/index: If category was successfully edited (via redirect)</li>
	 * <li>category/form: If validation fails (via include). Includes these view variables:</li>
	 * <ul>
	 *	<li>category: The current Category instance, empty or being added (but not validated)</li>
	 *	<li>errors: Array including per-field validation errors</li>
	 * </ul>
	 * </ul>
	 * @throws Exception if the current logged user is not assigned to the project
	 * @return void
	 */
    public function edit(){

        $cat = $this->retrieveCategory();

        if (isset($_POST["name"])) { // reaching via HTTP Post...
            try {
                $cat = $this->loadCategory($cat);

                // validate Category object
                $cat->checkIsValidForRegister(); // if it fails, ValidationException
                // update the Category object in the database
                $this->categoryMapper->update($cat);

                // POST-REDIRECT-GET
                $this->view->redirect("categories", "index");

            } catch (ValidationException $ex) {
                // Get the errors array inside the exepction...
                $errors = $ex->getErrors();
                // And put it to the view as "errors" variable
                $this->view->setVariable("errors", $errors);
            }
        }

        $this->view->setVariable("category", $cat);
        // render the view (/view/projects/form.php)
        $this->view->render("categories", "form");

    }

    /**
     * Action to delete a category
     *
     * This action should only be called via HTTP POST
     *
     * The expected HTTP parameters are:
     * <ul>
     * <li>name: name of the category (via HTTP POST)</li>
     * </ul>
     *
     * The views are:
     * <ul>
     * <li>category/index: If category was successfully deleted.
     * </ul>
     * @throws Exception if no name was provided
     * @throws Exception if no user is in session
     * @return void
     */
	public function delete(){
		$cat = $this->retrieveCategory();

		// Delete the category object from the database
		$this->categoryMapper->delete($category->getName());

		// POST-REDIRECT-GET
		// perform the redirection. More or less:
		// header("Location: index.php?controller=projects&action=view&id=project_id")
		// die();
		$this->view->redirect("categories", "index");
	}

	/**
	 * Checks the given info to retrieve a category.
	 * 
	 * Throws exceptions if info is not valid, or returns the category
	 * 
	 * @return Category
	 */
	private function retrieveCategory(): Category{

		if (!isset($_REQUEST["name"])) {
			throw new Exception("A category name is mandatory");
		}
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Operating with categories requires login");
		}

		// Get the Category object from the database
		$catName = $_REQUEST["name"];
		$cat = $this->categoryMapper->find($catName);

		// Does the category exist?
		if ($cat == NULL) {
			throw new Exception("no such category with name: ".$catName);
		}

		return $cat;
	}

	/**
	 * Checks the given info to populate a category.
	 * Returns the task
	 * 
	 * @return Category
	 */
	private function loadCategory($category): Category {

		$category->setName($_POST["name"]);
		$category->setDesc($_POST["desc"]);
		
		return $category;
	}

}
?>