<?php
// file: model/Category.php

require_once(__DIR__."/../core/ValidationException.php");
require_once(__DIR__."/../core/I18n.php");
require_once(__DIR__."/../model/Category.php");

/**
 * Class Category
 *
 * Represents a Category in the app
 *
 */
class Category{

	/**
	 * The name of the Category
	 * @var string
	 */
	private $name;

	/**
	 * The description of the Category
	 * @var string
	 */
	private $desc;

	/**
	 * The constructor
	 *
	 * @param string $name The name of the Category
	 * @param string $desc The mail of the Category
	 */
	public function __construct($name = NULL, $desc = NULL)
	{
		$this->name = $name;
		$this->desc = $desc;
	}

	/**
	 * Gets the name of this Category
	 *
	 * @return string The name of this Category
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name of this Category
	 *
	 * @param string $name The name of this Category
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Gets the desc of this Category
	 *
	 * @return string The desc of this Category
	 */
	public function getDesc()
	{
		return $this->desc;
	}

	/**
	 * Sets the desc of this Category
	 *
	 * @param string $desc The user_mail of this Category
	 * @return void
	 */
	public function setDesc($desc)
	{
		$this->desc = $desc;
	}

	/**
	 * Checks if the current Category instance is valid
	 * for being registered in the database
	 *
	 * @throws ValidationException if the instance is
	 * not valid
	 *
	 * @return void
	 */
	public function checkIsValidForRegister()
	{
		$errors = array();

		//Comprobacion de PK lo hace el controller, aqui solo comprueba formatos

		if (strlen(trim($this->name)) < 1) {
			$errors["name"] = i18n("nombre es obligatorio");
		}
		if (strlen(trim($this->passwd)) < 6) {
			$errors["desc"] = i18n("Descripcion es obligatoria");
		}

		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, i18n("categoria no valida"));
		}
	}
}
?>