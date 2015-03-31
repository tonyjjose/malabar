<?php


/**
 * Class Instructor
 *
 * The base class 
 */


class Instructor extends user
{

    function __construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $approved, $active, $anon, $created, $last_login)
    {
    	parent::__construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, COURSE_MODE_EMAIL, ROLE_INSTRUCTOR, $approved, $active, $anon, $created, $last_login);
    }
    /**
     * Get all instructors from DB, return an array of instructor objects 
     * 
     *
     */
    public static function getAllInstructors()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM users WHERE user_type = '".ROLE_INSTRUCTOR."' ORDER BY user_name ASC";

        $query = $db->query($sql);   
        $rows = $query->fetchAll();

        //the list of objects
        $instructors = array();

        foreach ($rows as $row) {
            $instructors[] = new Instructor($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $instructors;      
    }

    public static function getAllInstructorsDoingCourse($course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM users WHERE user_type = '".ROLE_INSTRUCTOR."' AND user_id IN 
        (SELECT DISTINCT instructor_id FROM student_course WHERE course_id = :course_id) ORDER BY user_name ASC";

        $query = $db->prepare($sql);
        $query->execute(array(':course_id' => $course_id));  
        $rows = $query->fetchAll();

        //the list of objects
        $instructors = array();

        foreach ($rows as $row) {
            $instructors[] = new Instructor($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $instructors; 
    }    

    public static function getMyCourses($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM courses WHERE course_id IN (SELECT course_id FROM student_course WHERE instructor_id = :id) ORDER BY course_name ASC";

        $query = $db->prepare($sql);
        $query->execute(array(':id' => $id));   
        $rows = $query->fetchAll();

        //the list of objects
        $courses = array();

        foreach ($rows as $row) {
            $courses[] = new Course($row->course_id,$row->course_name,$row->course_desc,$row->course_active, Category::getInstance($row->course_category_id));
        }

        return $courses;        
    }
    public static function getMyCourseStudents($instructor_id,$course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM users WHERE user_id IN (SELECT DISTINCT student_id FROM student_course WHERE instructor_id = :instructor_id AND course_id = :course_id AND (course_status = :status_active OR course_status = :status_inactive)) ORDER BY user_name ASC";

        $query = $db->prepare($sql);
        $query->execute(array(':instructor_id' => $instructor_id,':course_id'=> $course_id, ':status_active'=>COURSE_INSTANCE_ACTIVE,
            ':status_inactive'=>COURSE_INSTANCE_INACTIVE));   
        $rows = $query->fetchAll();

        //the list of objects
        $students = array();

        foreach ($rows as $row) {
            $students[] = new Student($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode, $row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $students;        
    }
    public static function getLatestAssignments($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();  
        
        $query = $db->prepare("SELECT ASGN.* FROM assignments AS ASGN, student_course AS ST_C WHERE ASGN.student_id = ST_C.student_id 
            AND ASGN.course_id = ST_C.course_id AND ST_C.instructor_id = :id ORDER BY ASGN.upload_date DESC LIMIT 20");
        $query->execute(array(':id' => $id));

        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return null;
        }

        $rows = $query->fetchAll();
        $assignments = array();

        foreach ($rows as $row) {
            $assignments[] = new Assignment($row->assignment_id, Student::getInstance($row->student_id), Course::getInstance($row->course_id),
            $row->assignment_file, $row->assign_desc, $row->upload_date);
        }
        return $assignments;         
    }
    public static function getAllAssignments($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();  
        
        $query = $db->prepare("SELECT ASGN.* FROM assignments AS ASGN, student_course AS ST_C WHERE ASGN.student_id = ST_C.student_id 
            AND ASGN.course_id = ST_C.course_id AND ST_C.instructor_id = :id ORDER BY ASGN.upload_date DESC");
        $query->execute(array(':id' => $id));

        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return null;
        }

        $rows = $query->fetchAll();
        $assignments = array();

        foreach ($rows as $row) {
            $assignments[] = new Assignment($row->assignment_id, Student::getInstance($row->student_id), Course::getInstance($row->course_id),
            $row->assignment_file, $row->assign_desc, $row->upload_date);
        }
        return $assignments;         
    }

    public static function getInstructorForAssignment($student_id, $course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $query = $db->prepare("SELECT instructor_id FROM student_course WHERE student_id = :id AND course_id = :course_id");        
        $query->execute(array(':id' => $student_id,':course_id' => $course_id));
        
        $row = $query->fetch();

        //if nothing return null 
        if (empty($row)) {return null;}   

        return Instructor::getInstance($row->instructor_id);
    }
    /**
     * Check for user type instructor.
     * @return bool status
     */
    public static function isUserInstructor($id)
    {
        if (User::getUserType($id) == ROLE_INSTRUCTOR) {
           return true;
        }
        return false;
    }      
}