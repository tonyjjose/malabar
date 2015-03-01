<?php

/**
 * Course class
 *
 * Handles the course bussiness object
 */


class Course
{
    //properties
    private $id=0;
    private $name;
    private $desc;
    private $active = true;
    private $category = null;

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
    public function getActive(){
        return $this->active;
    }    
    public function getCategory(){
        return $this->category;
    }    

    //constructor
    function __construct($id, $name, $desc, $active, Category $category)
    {  
        $this->id = (is_int($id)) ? $id : (int)$id ;
        $this->name = $name;
        $this->desc = $desc;  
        //$this->active = $active;
        $this->active = (is_bool($active)) ? $active : filter_var($active, FILTER_VALIDATE_BOOLEAN);
        $this->category = $category;
    } 

    //get instance from DB
    public static function getInstance($id){

        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT * FROM courses WHERE course_id = :id LIMIT 1");
        $query->execute(array(':id' => $id));
        $row = $query->fetch(); 

        //if nothing return null 
        if (empty($row)) {return null;}

        return new Course($row->course_id,$row->course_name,$row->course_desc,$row->course_active, 
            Category::getInstance($row->course_category_id));      
    }
    /**
     * Get all courses from DB, return an array of course objects 
     * 
     *
     */
    public static function getAllCourses()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM courses ORDER BY course_name ASC");   
        $rows = $query->fetchAll();

        //the list of objects
        $courses = array();

        foreach ($rows as $row) {
            $courses[] = new Course($row->course_id,$row->course_name,$row->course_desc,$row->course_active, 
                Category::getInstance($row->course_category_id));
        }

        return $courses;
        
    }


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
     * Get all courses rows from db
     * 
     *
     */

    public static function getAllCoursesRows()
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


	
}

/**
 * CourseInstance User
 *
 * A simple object to hold the information of a particular instance of course done by a student.
 */


class CourseInstance
{
    private $course;
    private $instructor;
    private $status;

    function __construct (Course $course, Instructor $instructor, $status) {
        $this->course = $course;
        $this->instructor = $instructor;
        $this->status = $status;

    }
    public function getCourse(){
        return $this->course;
    }
    public function getInstructor(){
        return $this->instructor;
    }
    public function getStatus(){
        return $this->status;
    }
}

   


