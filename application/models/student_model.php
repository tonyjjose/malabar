<?php

/**
 * UserModel
 *
 * Handles the users related bussiness logic
 */

class StudentModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */        

    public function editSave()
    {

     //get the inputs
        $id = Request::post('user_id');
        $name = Request::post('user_name');
        $email = Request::post('user_email');
        $age = (int)Request::post('user_age');
        $sex = Request::post('user_sex');
        $qual = Request::post('user_qual');
        $bio = Request::post('user_bio');
        $phone = Request::post('user_phone');
        $mobile = Request::post('user_mobile');
        $address = Request::post('user_address');
        $mode = Request::post('user_course_mode');

        //since it is a checkbox it wont be set if it was not checked by user.
        $anon = (Request::post('user_anon') == 'yes') ? YES : NO;        

        //ok we have the inputs, validate them
        if(!$name || strlen($name)== 0 || strlen($name) > 64) {
            Feedback::addNegative('Failed! user name is invalid.');
            return false;
        }  
        if(strlen($email) > 64 || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            Feedback::addNegative('Failed! user email not valid.');
            return false;
        }              
        //Check if the user email already exist for another user
        if (User::emailExistsForAnotherUser($email,$id)) {
            Feedback::addNegative('Failure! user email already exists.');
            return false;
        }
        if($age < 14 || $age > 99) {
            Feedback::addNegative('Failed! user should be older than 14.');
            return false;
        }  

        //OK try to add to db       
        $success = Student::update($id, $name, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $mode, $anon); 

        //has it got updated? if so success.
        if ($success) {
            Feedback::addPositive("Success! user '{$name}' updated.");
            return true;
        }  

        //We come here if its not updated properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;
        
    }  

    public function getCourseMates ($course_id)
    {
        $id = Session::get('user_id');

        if (!(Student::isStudentDoingCourse($id,$course_id))) {
            Feedback::addNegative('You do not do this course');
            echo "string";
            return null;
        }

        return Student::getCourseMates($id,$course_id);


    }
}