<?php

/**
 * Category class
 *
 * Handles the Course Category
 * As of now the idea is to represent languages
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

    //get instance from DB
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
     * Get all categories from DB, return an array of category objects 
     * 
     *
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
     * Check if a category already exists.
     * 
     *
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
}


