<?php

/**
 * CourseModel
 *
 * Handles the courses stuff in db
 */

class CourseModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function saveCategory(){

    	//get the inputs
    	$name = Request::post('category_name');
    	$desc = Request::post('category_desc');

    	//validate them
    	if(!$name || strlen($name)== 0 || strlen($name) > 15) {
    		Feedback::addNegative("Failed! Course Category name is invalid.");
    		return false;
    	}

    	//Check if the category name already exist
    	//if ($this->categoryExists($name)) {
    	if (Course::categoryExists($name)) {
    		Feedback::addNegative("Failure! Course Category name already exists.");
    		return false;
    	}

    	//ok, try to add to db
    	$sql = "INSERT INTO category (cat_name, cat_desc) VALUES (:name, :desc)";
    	$query = $this->db->prepare($sql);
    	$query->execute(array(':name'=>$name,':desc'=>$desc));

    	//has it got added? if so success.
    	if ($query->rowCount() == 1) {
    		Feedback::addPositive("Success! Course category '{$name}' added.");
            return true;
        }  

    	//We come here if its not added properly, notify it and exit
    	Feedback::addNegative("Failed! Unknown reason.");
    	return false;

    }

    /**
     * Check if a category already exists.
     * Should we move this to a special class??
     *
     */
    public function categoryExists($name)
    {
    	$query = $this->db->prepare("SELECT cat_id FROM category WHERE cat_name = :name LIMIT 1");
        $query->execute(array(':name' => $name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }
}
