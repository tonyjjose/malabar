<?php

/**
 * ReportModel
 *
 * Handles the reports related bussiness logic.
 */

class ReportModel
{     

    /**
     * The list of students based on the selection criterea. 
     * @return array() student object
     */ 
    public function studentList()
    {
        $course_id = Request::post('course_id');
        $mode = Request::post('mode');
        $status = Request::post('status');  
        
        $sql;

        //parse the mode input
        if ($mode == 'B') {
        	$mode = "(user_course_mode = '".COURSE_MODE_EMAIL."' OR user_course_mode = '".COURSE_MODE_POSTAL."')";
        } else {
        	$mode = "user_course_mode = '{$mode}'";
        }

        //parse the status input
        if($status == 'All') {
            $status = "1=1"; //simple trick to drop that condition
        } else {
            $status = "course_status = '{$status}'";
        }

        //parse the course_id input
        if($course_id == 'All')
        {
        	$sql = "SELECT * FROM users WHERE user_id IN (SELECT DISTINCT student_id FROM student_course WHERE
        	 {$status}) AND {$mode} AND user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC";
        }
        elseif ($course_id == 'None') {
        	$sql = "SELECT * FROM users WHERE user_id NOT IN (SELECT DISTINCT student_id FROM student_course) AND 
             {$mode} AND user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC";
        }
        else 
        {
        	$sql = "SELECT * FROM users WHERE user_id IN (SELECT DISTINCT student_id FROM student_course WHERE
        	 course_id = '{$course_id}' AND {$status}) AND {$mode} AND user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC";        	
        }

        //var_dump($sql);
        return Student::getStudents($sql);       
    }  

    /**
     * The list of instructors based on the selection criterea. 
     * @return array() instructor object
     */ 
    public function instructorList()
    {
        $course_id = Request::post('course_id');
        $sql;

        //parse the course_id
        if ($course_id == 'Any') {
                $sql = "SELECT * FROM users WHERE user_type = '".ROLE_INSTRUCTOR."' AND user_id IN 
        (SELECT DISTINCT instructor_id FROM student_course WHERE course_status <> '".COURSE_INSTANCE_COMPLETED."') ORDER BY user_name ASC";
        } 
        elseif ($course_id == 'None') {
                $sql = "SELECT * FROM users WHERE user_type = '".ROLE_INSTRUCTOR."' AND user_id NOT IN 
        (SELECT DISTINCT instructor_id FROM student_course WHERE course_status <> '".COURSE_INSTANCE_COMPLETED."') ORDER BY user_name ASC";

        }
        else {
                $sql = "SELECT * FROM users WHERE user_type = '".ROLE_INSTRUCTOR."' AND user_id IN 
        (SELECT DISTINCT instructor_id FROM student_course WHERE course_id = '{$course_id}' AND course_status <> '".COURSE_INSTANCE_COMPLETED."') ORDER BY user_name ASC";

        }
        //var_dump($sql);
        return Instructor::getInstructors($sql);
    }
}