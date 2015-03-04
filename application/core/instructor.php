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
            $instructors[] = new User($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode,ROLE_INSTRUCTOR,$row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $instructors;      
    }

}