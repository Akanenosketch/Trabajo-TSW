<?php

require_once(__DIR__."/../model/Category.php");
require_once(__DIR__."/../model/CategoryMapper.php");

require_once(__DIR__."/BaseRest.php");

/**
 * Class CategoryRest
 *
 * It contains operations for CRUDL Categories.
 *
 * Methods gives responses following Restful standards. Methods of this class
 * are intended to be mapped as callbacks using the URIDispatcher class.
 *
 */
class CategoryRest extends BaseRest{

    private $categoryMapper;

    public function __construct(){
        parent::__construct();

        $this->categoryMapper = new CategoryMapper();
    }

    public function getCategories()
    {
        $currentUser = parent::authenticateUser();

        $cats = $this->categoryMapper->findAll();
        $encodedCats = array();
        foreach ($cats as $cat) {
            array_push($encodedCats, $this->encodeCat($cat));
        }
        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo (json_encode($encodedCats));
    }

    public function getCategory($catName): void{
        $cat = $this->retrieveCat($catName);
        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo (json_encode($this->encodeCatWithDesc($cat)));
    }

    public function createCategory($data){
        $currentUser = parent::authenticateUser();
        $cat = new Category();

        if (isset($data->name) && isset($data->desc)) {
            $cat = $this->loadCat($cat, $data);
        }
        try {
            // validate Cat object
            $cat->checkIsValidForRegister(); // if it fails, ValidationException

            // save the Cat object into the database
            $this->categoryMapper->save($cat);

            // response OK. Also send cat in content
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header('Location: '.$_SERVER['REQUEST_URI']."/".$cat->getName());
            header('Content-Type: application/json');
            $encoded_cat = $this->encodeCat($cat);
            echo (json_encode($encoded_cat));
        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo (json_encode($e->getErrors()));
        }
    }

    public function updateCategory($catName, $data){

        $cat = $this->retrieveCat($catName);
        $cat = $this->loadCat($cat, $data);
        try {
            // validate Category object
            $cat->checkIsValidForRegister(); // if it fails, ValidationException
            // update the Category object in the database
            $this->categoryMapper->update($cat);
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo (json_encode($e->getErrors()));
        }
    }

    public function deleteCategory($catName){
        $cat = $this->retrieveCat($catName);
        $this->categoryMapper->delete($cat);
        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }

    private function retrieveCat($catName, $currentUser = NULL){
        if ($currentUser == NULL)
            $currentUser = parent::authenticateUser();

        // Get the Category object from the database
        $cat = $this->categoryMapper->findAllWithDesc($catName);
        if ($cat == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo ("Category with name ".$catName." not found");
            die;
        }
        return $cat;
    }

    private function loadCat($cat, $data){

        $cat->setName($data->name);
        $cat->setDesc($data->desc);

        return $cat;
    }

    private function encodeCat($cat){
        $encoded = array(
            "name" => $cat->getName()
        );

        return $encoded;
    }

    private function encodeCatWithDesc($cat){
        $encoded = array(
            "desc" => $cat->getDesc(),
            "name" => $cat->getName()
        );

        return $encoded;
    }

}

// URI-MAPPING for this Rest endpoint
$categoryRest = new CategoryRest();
URIDispatcher::getInstance()
    ->map("GET", "/categories", array($categoryRest, "getCategories"))
    ->map("GET", "/categories/$1", array($categoryRest, "getCategory"))
    ->map("POST", "/categories"."/", array($categoryRest, "createCategory"))
    ->map("PUT", "/categories/$1", array($categoryRest, "updateCategory"))
    ->map("DELETE", "/categories/$1", array($categoryRest, "deleteCategory"));