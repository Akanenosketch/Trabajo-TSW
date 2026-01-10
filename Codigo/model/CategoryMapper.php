<?php
// file: model/CategoryMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
 * Class CategoryMapper
 *
 * Database interface for Category entities
 *
 */
class CategoryMapper{

    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct(){
        $this->db = PDOConnection::getInstance();
    }




    //findAll solo de nombres
//findAllWithDesc para todo



    /**
     * Saves a Category into the database
     *
     * @param Category $cat The Category to be saved
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function save($cat){
        $stmt = $this->db->prepare("INSERT INTO categories(cat_name,cat_desc) values (?,?)");
        $stmt->execute(array($cat->getName(), $cat->getDesc()));
    }




    /**
     * Checks if a given Category name is already in the database
     *
     * @param string $name the name to check
     * @return boolean true if the name exists, false otherwise
     */
    public function categoryExists($name){
        $stmt = $this->db->prepare("SELECT count(cat_name) FROM categories where cat_name=?");
        $stmt->execute(array($name));

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Loads a Category from the database given its name
     *
     *
     * @throws PDOException if a database error occurs
     * @return Category The Category instance. NULL
     * if the Category is not found
     */
    public function find($name){
        $stmt = $this->db->prepare("SELECT * FROM categories where cat_name=?");
        $stmt->execute(array($name));
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cat != null) {
            return new Category(
                $cat["cat_name"],
                $cat["cat_desc"]
            );
        } else {
            return NULL;
        }
    }

    /**
     * Updates a Category in the database
     *
     * @param Category $cat The Category to be updated
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function update(Category $cat){
        $stmt = $this->db->prepare("UPDATE categories set cat_desc=? where cat_name=?");
        $stmt->execute(array($cat->getDesc(), $cat->getName()));
    }

    /**
	 * Deletes a Category from the database
	 *
	 * @param Category $cat The Category to be deleted
	 * @throws PDOException if a database error occurs
	 * @return void
	 */
	public function delete(Category $cat): void{
		$stmt = $this->db->prepare("DELETE from categories WHERE cat_name=?");
		$stmt->execute(array($cat->getName()));
	}

    public function findAll(){
        $stmt = $this->db->prepare("SELECT cat_name FROM categories");
        $stmt->execute();
        $cats_DB = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cats = array();

        foreach ($cats_DB as $car) {
            array_push($cats,$car["cat_name"]);
        }
        return $cats;
    }

    public function findAllWithDesc(){
            $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        $cats_DB = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cats = array();

        foreach ($cats_DB as $car) {
            array_push($cats,new Category(
                $car["cat_name"],
                $car["cat_desc"]
        ));
        }
        return $cats;
    
    }


}
?>