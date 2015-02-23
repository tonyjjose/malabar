<?php

/**
 * Course class
 *
 * Handles the course bussiness object
 */


class Course
{




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