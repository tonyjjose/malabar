<?php

/**
 * CategoryObject class
 *
 * This is the course category object. It provides static methods for DB operations as well us helper methods
 * for various view purposes.
 * As of now, we use the categories to represent languages
 */

class Category
{
    //properties
    private $id=0;
    private $name;
    private $desc;

    //getter and setter
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getDescription(){
        return $this->desc;
    }

    //constructor
    function __construct($id, $name, $desc)
    {  
       $this->id = (is_int($id)) ? $id : (int)$id ;
       $this->name = $name;
       $this->desc = $desc;  
    } 

    /**
     * Create category instance.
     * @return object Category or null
     */
    public static function getInstance($id){

        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT * FROM category WHERE cat_id = :id LIMIT 1");
        $query->execute(array(':id' => $id));
        $row = $query->fetch(); 

        //if nothing return null 
        if (empty($row)) {return null;}

        return new Category($row->cat_id,$row->cat_name,$row->cat_desc);      
    }

    /**
     * Create all category object array
     * @return array[] of object Category or null
     */
    public static function getAllCategories()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM category ORDER BY cat_name ASC");   
        $rows = $query->fetchAll();

        //the list of objects
        $categories = array();

        foreach ($rows as $row) {
            $categories[] = new Category($row->cat_id,$row->cat_name,$row->cat_desc);
        }

        return $categories;       
    }

    /**
     * Save category to DB
     *  @return bool success state
     */
    public static function save($name, $desc)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //ok, try to add to db
        $sql = "INSERT INTO category (cat_name, cat_desc) VALUES (:name, :desc)";
        $query = $db->prepare($sql);
        $query->execute(array(':name'=>$name,':desc'=>$desc));

        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }  
        return false;
    } 
    /**
     * Check if a category already exists.
     * 
     * Used to check for name collisions while adding new category
     *  @return bool success state
     */
    public static function categoryExists($name)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //Query the DB
        $query = $db->prepare("SELECT cat_id FROM category WHERE cat_name = :name LIMIT 1");
        $query->execute(array(':name' => $name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Create all category details array.
     * Not used anymore, just left it undeleted
     * @return array[] or null
     */
    public static function getAllCourseCategoryNames()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT cat_id, cat_name FROM category ORDER BY cat_name ASC");   
        return $query->fetchAll();  
    }    
}