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

            $sql = "SELECT * FROM users WHERE user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC"; 

        $query = $db->query($sql);   
        $rows = $query->fetchAll();

        //the list of objects
        $students = array();

        foreach ($rows as $row) {
            $students[] = new User($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode, ROLE_STUDENT, $row->user_approved,$row->user_active,
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
            $instructor = User::getInstance($row->instructor_id);
            $this->courseInstances[] = new CourseInstance ($course,$instructor,$row->course_status);            
        }     
    } 

    public function getMyCourses(){
        return $this->courseInstances;
    }

    //note that we do not update password here
    public static function update($id, $name, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, $anon)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET user_name = :name, user_email = :email, user_age = :age, user_sex = :sex,
            user_qualification = :qual, user_bio = :bio, user_phone = :phone, user_mobile = :mobile,
            user_address = :address, user_course_mode = :mode, user_anonymous = :anon WHERE user_id =:id";
        $query = $db->prepare($sql);
        $query->execute(array(':name'=>$name,':email'=>$email,':age'=>$age,':sex'=>$sex,':qual'=>$qual,':bio'=>$bio,
            ':phone'=>$phone,':mobile'=>$mobile,':address'=>$address,':mode'=>$course_mode,':anon'=>$anon,':id'=>$id));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false; 
    }    
    public static function getCourseMates($student_id, $course_id)
    {

        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT student_id FROM student_course WHERE student_id <> :student_id 
            AND course_id = :course_id AND course_status = 'A'");
        $query->execute(array(':student_id' => $student_id, ':course_id' => $course_id));

        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return array();
        }
        
        $rows = $query->fetchAll();     
        $mates = array();
                
        foreach ($rows as $row) {
            $mates[] = Student::getInstance($row->student_id);            
        } 

        return $mates;
    }
        
    public static function getCourseInstance($student_id,$course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();  
                      //query the DB
        $query = $db->prepare("SELECT * FROM student_course WHERE student_id = :student_id 
            AND course_id = :course_id");
        $query->execute(array(':student_id' => $student_id, ':course_id' => $course_id));

        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return null;
        }

        $row = $query->fetch();

        $course = Course::getInstance($row->course_id);
        $instructor = User::getInstance($row->instructor_id);           
        $courseInstance = new CourseInstance($course, $instructor, $row->course_status);          

        return $courseInstance;
    } 

    public static function saveCourseInstance($student_id,$course_id,$instructor_id,$status)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $sql = "INSERT INTO student_course (student_id, course_id, instructor_id, course_status) VALUES
             (:student_id, :course_id, :instructor_id, :status)";
        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$student_id,'course_id'=>$course_id,
            'instructor_id'=>$instructor_id,'status'=>$status));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }  

    public static function updateCourseInstance($student_id,$course_id,$instructor_id,$status)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $sql = "UPDATE student_course SET instructor_id = :instructor_id, course_status = :status WHERE
             student_id = :student_id AND course_id = :course_id";
        $query = $db->prepare($sql);
        $query->execute(array('instructor_id'=>$instructor_id,'status'=>$status,
            'student_id'=>$student_id,'course_id'=>$course_id));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }     

    public static function deleteCourseInstance($student_id,$course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM student_course WHERE student_id = :student_id AND course_id = :course_id";
        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$student_id,'course_id'=>$course_id));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;

    }

    public static function getUnEnrolledCourses($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();      
        
        $sql = "SELECT course_id FROM courses WHERE course_id NOT IN
             (SELECT course_id from student_course WHERE student_id = :student_id)"; 

        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$id));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return array();
        }
        
        $rows = $query->fetchAll();     
        $courses = array();
                
        foreach ($rows as $row) {
            $courses[] = Course::getInstance($row->course_id);            
        } 

        return $courses;            

    }

    public static function isUserStudent($id)
    {
        if (User::getUserType($id) == ROLE_STUDENT) {
           return true;
        }
        return false;
    }


}