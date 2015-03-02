<?php


/**
 * Course User
 *
 * The base class 
 */


class Student extends User
{
    private $courseInstances = array(); //var to hold his participating courses' 

    function __construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, $approved, $active, $anon, $created, $last_login)
    {
    	parent::__construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, ROLE_STUDENT, $approved, $active, $anon, $created, $last_login);
    }
    /**
     * Get all students from DB, return an array of student objects 
     * 
     *
     */
    public static function getAllStudents()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM users WHERE user_type = 'ROLE_STUDENT' ORDER BY user_name ASC");   
        $rows = $query->fetchAll();

        //the list of objects
        $students = array();

        foreach ($rows as $row) {
            $students[] = new Student($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode,$row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $students;
        
    }   
          /**
     * Get a course from ID.
     * 
     *
     */
    public function loadMyCourses()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT * FROM student_course WHERE student_id = :id");
        $query->execute(array(':id' => $this->getId()));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return array();
        }

        //We have some cousres, so return it 
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $course = Course::getInstance($row->course_id);
            $instructor = Instructor::getInstance($row->instructor_id);
            $this->courseInstances[] = new CourseInstance ($course,$instructor,$row->course_status);            
        }     
    } 

    public function getMyCourses(){
        return $this->courseInstances;
    }


}