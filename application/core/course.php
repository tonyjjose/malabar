<?php

/**
 * Course class
 *
 * Handles the course bussiness object
 */


class Course
{
    /**
     * Get all categories, return an 
     * Should we move this to a special class??
     *
     */

	public static function getAllCourseCategoryNames()
	{
        $db = DatabaseFactory::getFactory()->getConnection();

    	$query = $db->query("SELECT cat_id, cat_name FROM category ORDER BY cat_name ASC");   
        return $query->fetchAll();
        
	}

    /**
     * Check if a course already exists.
     * Should we move this to a special class??
     *
     */
    public static function courseExists($name)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

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

    	$query = $db->prepare("SELECT cat_id FROM category WHERE cat_name = :name LIMIT 1");
        $query->execute(array(':name' => $name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

	
}