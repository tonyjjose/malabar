<?php

/**
 * UserModel
 *
 * Handles the users related bussiness logic
 */

class ReportModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */        

    public function studentList()
    {
        $course_id = Request::post('course_id');
        $mode= Request::post('mode');
        $status = Request::post('status');  
        $sql;

        if ($mode == 'B') {
        	$mode = "(user_course_mode = 'E' OR user_course_mode = 'P')";
        }
        else
        {
        	$mode = "user_course_mode = '{$mode}'";
        }

        if($course_id == 'All')
        {
        	$sql = "SELECT * FROM users WHERE user_id IN (SELECT DISTINCT student_id FROM student_course WHERE
        	 course_status = '{$status}') AND {$mode} AND user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC";

        }
        elseif ($course_id == 'None') {
        	$sql = "SELECT * FROM users WHERE user_id NOT IN (SELECT DISTINCT student_id FROM student_course) AND {$mode} AND user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC";
        }
        else 
        {
        	$sql = "SELECT * FROM users WHERE user_id IN (SELECT DISTINCT student_id FROM student_course WHERE
        	 course_id = '{$course_id}' AND course_status = '{$status}') AND {$mode} AND user_type = '".ROLE_STUDENT."' ORDER BY user_name ASC";        	
        }

        $db = DatabaseFactory::getFactory()->getConnection();
    	$query = $db->query($sql);

        $rows = $query->fetchAll();

        //var_dump($sql);

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

}
