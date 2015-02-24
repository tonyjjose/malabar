<?php

/**
 * Course class
 *
 * Handles the course bussiness object
 */


class Course
{

     /**
     * Get a course from ID.
     * 
     *
     */
    public static function getCourse($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT * FROM courses WHERE course_id = :id LIMIT 1");
        $query->execute(array(':id' => $id));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return null;
        }

        //it will raise a DB warning, if there is no row, so we checked it in the prev line.
        return $query->fetch();
    } 

    /**
     * Get all courses
     * 
     *
     */

    public static function getAllCourses()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM courses ORDER BY course_name ASC");   
        return $query->fetchAll();
        
    }



    /**
     * Get all categories, return an 
     * 
     *
     */

	public static function getAllCourseCategoryNames()
	{
        $db = DatabaseFactory::getFactory()->getConnection();

    	$query = $db->query("SELECT cat_id, cat_name FROM category ORDER BY cat_name ASC");   
        return $query->fetchAll();
        
	}

     /**
     * Get a course name from ID.
     * 
     *
     */
    public static function getCourseName($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT course_name FROM courses WHERE course_id = :id LIMIT 1");
        $query->execute(array(':id' => $id));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return null;
        }

        //it will raise a DB warning, if there is no row, so we checked it in the prev line.
        return $query->fetch()->course_name;
    }   

    /**
     * Check if a given course name already exists for other couses.
     * @name, @id Name and ID of the current course
     *
     */
    public static function otherCourseExists($name, $id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT course_id FROM courses WHERE course_name = :name AND course_id <> :id LIMIT 1");
        $query->execute(array(':name' => $name, ':id' => $id));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }
    /**
     * Check if a course already exists.
     * 
     *
     */
    public static function courseExists($name)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
    	$query = $db->prepare("SELECT course_id FROM courses WHERE course_name = :name LIMIT 1");
        $query->execute(array(':name' => $name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }
    /**
     * Check if a category already exists.
     * Should we move this to a special class??
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

	
}