<?php

/**
 * CourseModel class
 *
 * Handles the courses/categories related bussiness logic.
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

    /**
    * New course adding process.
    *
    * We assume that all new courses are active.
    * @return bool success state
    */
    public function addSave()
    {
        //get the inputs
        $name = trim(Request::post('course_name'));
        $desc = Request::post('course_desc');
        $cat_id = Request::post('course_cat');

        //validate them
    	if(!$name || strlen($name) == 0 || strlen($name) > 25) {
    		Feedback::addNegative('Failed! Course name is invalid.');
    		return false;
    	}  
        if(strlen($desc) > 255) {
            Feedback::addNegative('Failed! Course description is too large.');
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
        $success = Course::save($name, $desc, $cat_id);

    	//has it got added? if so success.
    	if ($success) {
    		Feedback::addPositive("Success! Course '{$name}' added.");
            return true;
        }  

    	//We come here if its not added properly, notify it and exit
    	Feedback::addNegative('Failed! Unknown reason.');
    	return false;
    }

    /**
    * Edit course process.
    * @return bool success state
    */
    public function editSave()
    {
        //get the inputs
        $id = Request::post('course_id');
        $name = trim(Request::post('course_name'));
        $desc = Request::post('course_desc');
        $cat_id = Request::post('course_cat');
        // a ternary conditional to get 1 or 0 for 'Course_Active'
        $active = (Request::post('course_active') == 'yes') ? YES : NO;

        //validate them
        if(!$name || strlen($name) == 0 || strlen($name) > 25) {
            Feedback::addNegative('Failed! Course name is invalid.');
            return false;
        }   
        if(strlen($desc) > 255) {
            Feedback::addNegative('Failed! Course description is too large.');
            return false;
        }              
        //Check if the new course name already exist for other courses
        if (Course::otherCourseExists($name, $id)) {
            Feedback::addNegative('Failure! Course name already exists.');
            return false;
        }
        //check whether course category is provided
        if(!$cat_id) {
            Feedback::addNegative('Failure! A Course category must be selected.');
            return false;
        }
        /* If it is marked inactive, check whether the course is in use, before updating.
        *  A course marked Inactive are not taken by students or taught by us.
        */
        if ($active == NO) {
            if (Course::isCourseInUse($id)) {
                Feedback::addNegative ("Failure! Course taken by students, cannot mark inactive.");
                return false;
            }
        }

        //ok, try to update to db
        $success = Course::update($id, $name, $desc, $active, $cat_id);
        
        //has it got updated? if so success.
        if ($success) {
            Feedback::addPositive("Success! Course '{$name}' updated.");
            return true;
        }

        //We come here if its not updated properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;
    }

    /**
    * Delete course process.
    * @return bool success state
    */        
    public function deleteSave()
    {
        //get the inputs
        $id = Request::post('course_id');

        //validate it
        if(!$id) {
            //hacking attempt? how could we reach here without an id?
            Feedback::addNegative ('Failure! No Course to delete.');
            return false;
        }

        //is the course in use?
        if (Course::isCourseInUse($id)) {
            Feedback::addNegative ("Failure! Course taken by students, cannot be deleted");
            return false;
        }        

        //ok, lets try to delete
        $success = Course::delete($id);

        //has it got deleted?
        if ($success) {
            Feedback::addPositive("Success! Course (ID={$id}) deleted.");
            return true;
        }         

        //We come here if its not deleted properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');        
        return false;
    }    

    /**
    * New category adding process.
    * @return bool success state
    */
    public function saveCategory(){

        //get the inputs
        $name = trim(Request::post('category_name'));
        $desc = Request::post('category_desc');

        //validate them
        if(!$name || strlen($name) == 0 || strlen($name) > 15) {
            Feedback::addNegative('Failed! Course Category name is invalid.');
            return false;
        }
        if(strlen($desc) > 255) {
            Feedback::addNegative('Failed! Course Category description is too large.');
            return false;
        }
        //Check if the category name already exist
        if (Category::categoryExists($name)) {
            Feedback::addNegative("Failure! Course Category name already exists.");
            return false;
        }

        //ok, try to add to db
        $success = Category::save($name,$desc);
        
        //has it got added? if so success.
        if ($success) {
            Feedback::addPositive("Success! Course category '{$name}' added.");
            return true;
        }          

        //We come here if its not added properly, notify it and exit
        Feedback::addNegative("Failed! Unknown reason.");
        return false;
    }
}
