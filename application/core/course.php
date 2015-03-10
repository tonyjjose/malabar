<?php

/**
 * CourseObject class
 *
 * This is the course object. It provides static methods for DB operations as well us helper methods
 * for various view purposes.
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
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->desc;
    }
    public function getActive() {
        return $this->active;
    }    
    public function getCategory() {
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

    /**
     * Create course instance.
     * @return object course or null
     */
    public static function getInstance($id)
    {
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
     * Create all course object array
     * @return array[] of object course or null
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
     * Save course to DB.
     * 
     * we assume that all new courses are active. 
     * @return bool success state
     */
    public static function save($name, $desc, $cat_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //ok, try to update to db  
        $sql = "INSERT INTO courses (course_name, course_desc, course_category_id) VALUES (:name, :desc, :cat_id)";
        $query = $db->prepare($sql);
        $query->execute(array(':name'=>$name,':desc'=>$desc,'cat_id'=>$cat_id));

        //has it got updated? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }  
        return false;
    }

    /**
     * Update course to DB.
     * 
     * @return bool success state
     */
    public static function update($id, $name, $desc, $active, $cat_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //ok, try to update to db
        $sql = "UPDATE courses SET course_name = :name, course_desc = :desc, course_active = :active, 
            course_category_id = :cat_id WHERE course_id = :id";
        $query = $db->prepare($sql);
        $query->execute(array(':name'=>$name,':desc'=>$desc,':active'=>$active,'cat_id'=>$cat_id, 'id'=>$id));

        //has it got updated? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }  
        return false;
    }        

    /**
     * Delete course from DB.
     * 
     * @return bool success state
     */
    public static function delete($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //ok, lets try to delete
        $sql = "DELETE FROM courses WHERE course_id = :id";
        $query = $db->prepare($sql);
        $query->execute(array(':id'=>$id));
        
        //has it got deleted? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Check if a given course name already exists for another course.
     *
     * This is used while editing a courses name.
     * @param @name, @id Name and ID of the current course
     * @return bool whether course name exists for another course
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
     * Check for course name collision.
     *
     * This is used while adding a new course. Course name should be unique.
     * @param @name Name of the new course
     * @return bool whether course name already exists
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
     * Check if a course is in use.
     * 
     * This is used when deleting or making a course inactive.
     * @return bool use state
     */
    public static function isCourseInUse($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->prepare("SELECT course_id FROM student_course WHERE course_id = :id LIMIT 1");   
        $query->execute(array(':id'=>$id));

        return ($query->rowCount() == 1);        
    }

    /******  old functions *****************************************/
    /**
     * Create course details array from ID.
     * Not used anymore, just left it undeleted
     * @return array[] or null
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
     * Create all courses' details array.
     * Not used anymore, just left it undeleted
     * @return array[] or null
     */
    public static function getAllCoursesRows()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM courses ORDER BY course_name ASC");   
        return $query->fetchAll();    
    }

    /**
     * Get course name from ID.
     * Not used anymore, just left it undeleted
     * @return string coursename or null
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
}


   


