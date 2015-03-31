<?php

/**
 * StudentObject class
 *
 * This is the student object. It provides static methods for DB operations as well us helper methods
 * for various view purposes.
 */

class Student extends User
{
    private $courseInstances = array(); //var to hold his/her participating courses' 

    //getter
    public function getMyCourses(){
        return $this->courseInstances;
    }    

    function __construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, $approved, $active, $anon, $created, $last_login)
    {
    	parent::__construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, ROLE_STUDENT, $approved, $active, $anon, $created, $last_login);
    }

    /**
     * Fetches all courseInstances of a student.
     * And populates the courseIntance[] array.
     */
    public function loadMyCourses()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT * FROM student_course WHERE student_id = :id ORDER BY course_status");
        $query->execute(array(':id' => $this->getId()));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return;
        }

        //We have some courses
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $course = Course::getInstance($row->course_id);
            $instructor = User::getInstance($row->instructor_id);
            $this->courseInstances[] = new CourseInstance ($course,$instructor,$row->join_date,$row->course_status);            
        }     
    }     

    /**
     * Create all student object array
     * @return array[] of object student or null
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
            $students[] = new Student($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode, $row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $students;       
    }   

    /**
     * Update student to DB.
     * 
     * We do not update password here
     * @return bool success state
     */
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
        
        //has it got updated? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false; 
    }

    /**
    * Get a particular courseInstance of a student.
    */        
    public static function getCourseInstance($student_id,$course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();  
        
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
        $courseInstance = new CourseInstance($course, $instructor,$row->join_date, $row->course_status);          

        return $courseInstance;
    } 

    /**
     * Save a courseInstance to DB.
     * 
     * This happens when a student enrols for a new course 
     * @return bool success state
     */
    public static function saveCourseInstance($student_id, $course_id, $instructor_id, $join_date, $status)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $sql = "INSERT INTO student_course (student_id, course_id, instructor_id, join_date, course_status) VALUES
             (:student_id, :course_id, :instructor_id, :join_date, :status)";
        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$student_id, 'course_id'=>$course_id,
            'instructor_id'=>$instructor_id, 'join_date'=>$join_date, 'status'=>$status));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }  

    /**
     * Update a courseInstance to DB.
     * 
     * This happens usually when an instructor is changed or the student is no longer active
     * or has completed the course.
     * @return bool success state
     */
    public static function updateCourseInstance($student_id,$course_id,$instructor_id,$status)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $sql = "UPDATE student_course SET instructor_id = :instructor_id, course_status = :status WHERE
             student_id = :student_id AND course_id = :course_id";
        $query = $db->prepare($sql);
        $query->execute(array('instructor_id'=>$instructor_id,'status'=>$status,
            'student_id'=>$student_id,'course_id'=>$course_id));
        
        //has it got updated? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }     

    /**
     * Delete a courseInstance to DB.
     * 
     * This happens during disenrollment.
     * @return bool success state
     */
    public static function deleteCourseInstance($student_id,$course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM student_course WHERE student_id = :student_id AND course_id = :course_id";
        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$student_id,'course_id'=>$course_id));
        
        //has it got deleted? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Save a assignment to DB. 
     * @return bool success state
     */
    public static function saveAssignment($name, $desc, $date, $student_id, $course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $sql = "INSERT INTO assignments (student_id, course_id, assignment_file, assign_desc, upload_date) VALUES
             (:student_id, :course_id, :name, :desc, :date)";
        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$student_id, 'course_id'=>$course_id,
            'name'=>$name, 'date'=>$date, 'desc'=>$desc));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    } 

    /**
    * Get the list of assignments by a  sudents..
    * @return array[] assignment object
    */
    public static function getAllAssignments($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();  
        
        $query = $db->prepare("SELECT * FROM assignments WHERE student_id = :student_id 
            ORDER BY upload_date DESC");
        $query->execute(array(':student_id' => $id));

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

    /**
    * Get the list of other sudents taking the course.
    *
    * The list does not include the querying student. The list included only those students who are
    * actively participating in the course(ie status=A).
    * @return array[] student object
    */
    public static function getCourseMates($student_id, $course_id)
    {

        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        // $query = $db->prepare("SELECT student_id FROM student_course WHERE student_id <> :student_id 
        //     AND course_id = :course_id AND course_status = 'A'");
        $query = $db->prepare("SELECT * FROM users WHERE user_id IN (SELECT student_id FROM student_course WHERE student_id <> :student_id 
            AND course_id = :course_id AND course_status = 'A')ORDER BY user_name ASC");
        $query->execute(array(':student_id' => $student_id, ':course_id' => $course_id));

        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return array();
        }
        
        $rows = $query->fetchAll();     
        $mates = array();
                
        foreach ($rows as $row) {
            //$mates[] = Student::getInstance($row->student_id);
            $mates[] = new Student($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode, $row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);       
        } 

        return $mates;
    }

    /**
    * Get the list of enrolled courses for a student.
    *
    * The list only includes active courses(ie status=1).
    * @return array[] course object
    */
    public static function getEnrolledCourses($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();      
        
        $sql = "SELECT * FROM courses WHERE course_id IN (SELECT course_id FROM student_course WHERE student_id = :student_id)
         AND course_active ='".ACTIVE."' ORDER BY course_name ASC"; 

        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$id));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return array();
        }
        
        $rows = $query->fetchAll();     
        $courses = array();
                
        foreach ($rows as $row) 
        {
            // $courses[] = Course::getInstance($row->course_id); 
            $courses[] = new Course($row->course_id,$row->course_name,$row->course_desc,$row->course_active, 
                Category::getInstance($row->course_category_id));                       
        } 

        return $courses;
    }

    /**
    * Get the list of unenrolled courses for a student.
    *
    * The list only includes active courses(ie status=1).
    * @return array[] course object
    */
    public static function getUnEnrolledCourses($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();      
        
        $sql = "SELECT * FROM courses WHERE course_id NOT IN (SELECT course_id FROM student_course WHERE student_id = :student_id)
         AND course_active ='".ACTIVE."' ORDER BY course_name ASC"; 

        $query = $db->prepare($sql);
        $query->execute(array('student_id'=>$id));
        
        //is this check absolutely necessary??
        if ($query->rowCount() == 0) {
            return array();
        }
        
        $rows = $query->fetchAll();     
        $courses = array();
                
        foreach ($rows as $row) 
        {
            // $courses[] = Course::getInstance($row->course_id); 
            $courses[] = new Course($row->course_id,$row->course_name,$row->course_desc,$row->course_active, 
                Category::getInstance($row->course_category_id));                       
        } 

        return $courses;            
    }

    /**
     * Check for user type student.
     * @return bool status
     */
    public static function isUserStudent($id)
    {
        if (User::getUserType($id) == ROLE_STUDENT) {
           return true;
        }
        return false;
    }

    public static function isStudentMyCourseMate($id, $student_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();
        
        // $sql = "SELECT student_id FROM student_course JOIN student_course ON course_id = course_id and WHERE student_id = :id 
        //     AND course_id = :course_id AND course_status = 'A'";

        $sql = "SELECT SC1.student_id FROM student_course AS SC1, student_course AS SC2 WHERE SC1.course_id = SC2.course_id AND SC1.student_id = :id AND SC2.student_id = :student_id";

        $query = $db->prepare($sql);
        $query->execute(array('id'=>$id, 'student_id'=>$student_id)); 
        
        echo $query->rowCount();
        return ($query->rowCount() > 1);


    }

    /**
     * Check whether a student is doing a particular course.
     * Note that it doesnt check whether he as already dont it. 
     * @return bool status
     */
    public static function isStudentDoingCourse($id, $course_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection(); 

        $sql = "SELECT student_id FROM student_course WHERE student_id = :id 
            AND course_id = :course_id AND course_status = 'A'";

        $query = $db->prepare($sql);
        $query->execute(array('id'=>$id, 'course_id'=>$course_id)); 
        
        return ($query->rowCount() == 1);           
    }
}