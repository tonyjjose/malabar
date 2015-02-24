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

    public function addSave()
    {
        echo "Lets save to db";
        //get the inputs
        $name = Request::post('course_name');
        $desc = Request::post('course_desc');
        $cat_id = Request::post('course_cat');

        //validate them
    	if(!$name || strlen($name)== 0 || strlen($name) > 25) {
    		Feedback::addNegative('Failed! Course name is invalid.');
    		return false;
    	}        
    	//Check if the course name already exist
    	if (Course::courseExists($name)) {
    		Feedback::addNegative('Failure! Course name already exists.');
    		return false;
    	}
    	//check whether course category is provided
    	if(!$cat_id) {
    		Feedback::addNegative('Failure! A Course category must be selected.');
    		return false;
    	}

    	//ok, try to add to db
    	$sql = "INSERT INTO courses (course_name, course_desc, course_category_id) VALUES (:name, :desc, :cat_id)";
    	$query = $this->db->prepare($sql);
    	$query->execute(array(':name'=>$name,':desc'=>$desc,'cat_id'=>$cat_id));

    	//has it got added? if so success.
    	if ($query->rowCount() == 1) {
    		Feedback::addPositive("Success! Course '{$name}' added.");
            return true;
        }  

    	//We come here if its not added properly, notify it and exit
    	Feedback::addNegative('Failed! Unknown reason.');
    	return false;
    }

    public function deleteSave(){
        //get the inputs
        $id = Request::post('course_id');

        //validate it
        if(!$id) {
            //hacking attempt?
            Feedback::addNegative ('Failure! No Course to delete.');
            return false;
        }

        $sql = "DELETE FROM courses WHERE course_id = :course_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':course_id'=>$id));

        //has it got deleted?
        if ($query->rowCount() == 1) {
            Feedback::addPositive("Success! Course (ID={$id}) deleted.");
            return true;
        }        

        //We come here if its not deleted properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;
    }    

    public function saveCategory(){

    	//get the inputs
    	$name = Request::post('category_name');
    	$desc = Request::post('category_desc');

    	//validate them
    	if(!$name || strlen($name)== 0 || strlen($name) > 15) {
    		Feedback::addNegative('Failed! Course Category name is invalid.');
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
