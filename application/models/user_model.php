<?php

/**
 * UserModel class
 *
 * Handles the user related bussiness logic
 */

class UserModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * New user registration process.
     *
     * The same process is used for user/add (done by manager) and login/register (done by new student). 
     * The three properties $approved, $active and $type will not be set when done by student. The method takes care
     * of this by providing the default values when they are not set.
     * @return bool success state
     */
    public function addSave()
    {
        //get the inputs
        $name = Request::post('user_name');
        $email = Request::post('user_email');
        $password = trim(Request::post('user_password')); //trim will return empty string on NULL/unset var.
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

        /* The next three inputs will be set only by manager, so set default values if they are unset.
         * (they will be unset when the student himself register using login/register.)
         */
        
        //set the default value of $type to ROLE_STUDENT if unset.
        $type = Request::post('user_type');
        $type = (is_null($type)) ? ROLE_STUDENT : $type;

        //set the default value of $approved to NO if unset.
        $approved = (Request::post('user_approved') == 'yes') ? YES : NO;

        //set the default value of active to YES if unset
        $active = Request::post('user_active');       
        if (is_null($active)) { 
            $active = YES;
        }
        //it is set, so process it.
        else { 
            $active = ($active == 'yes') ? YES : NO; 
        }


        //ok we have the inputs, validate them
    	if(!$name || strlen($name) == 0 || strlen($name) > 64) {
    		Feedback::addNegative('Failed! user name is invalid.');
    		return false;
    	}  
        if(strlen($email) > 64 || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            Feedback::addNegative('Failed! user email not valid.');
            return false;
        }              
    	//Check if the user name already exist
    	if (User::emailExists($email)) {
    		Feedback::addNegative('Failure! user email already exists.');
    		return false;
    	}
        if(!$password || strlen($password) == 0 || strlen($password) > 8) {
            Feedback::addNegative('Failed! user password is invalid.');
            return false;
        }     
        if($age < 14 || $age > 99) {
            Feedback::addNegative('Failed! user should be older than 14.');
            return false;
        }  

        //looks like the inputs are fine. lets add to db

        //Note: this is a PHP 5.5.5+ function, but we use it with a compatibility lib
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        //create the time string to be put in DB.
        $created = date('Y-m-d H:i:s', time());

    	//ok, try to add to db
        $success = User::save($name, $password_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $mode, $type, $approved, $active, $anon, $created);

        if ($success) {
            Feedback::addPositive("Success! user '{$name}' added.");

            //lets mail the user about it.
            $mailer = new Mailer();
            $params = array (
                "_to" => $email,
                "_name" => $name,
                "_subject" => Mailer::mail('MAIL_NEWREG_STUDENT_SUBJECT'),
                "_msg" => Mailer::mail('MAIL_NEWREG_STUDENT'),
                "_stuName" => $name,
                "_stuEmail" => $email,
                "_stuPass" => $password);
            $mailer->newMail($params);
            $mailer->sendMail();

            //lets mail the manager about new student registration
            $params = array (
                "_to" => MAIL_ADMIN,
                "_name" => MAIL_ADMIN_NAME,
                "_subject" => Mailer::mail('MAIL_NEWREG_MANAGER_SUBJECT'),
                "_msg" => Mailer::mail('MAIL_NEWREG_MANAGER'),
                "_stuName" => $name,
                "_stuEmail" => $email,
                "_stuLink" => URL."user/edit/".User::getIdFromEmail($email));
            $mailer->newMail($params);
            $mailer->sendMail();
            
            return true;            
        }
        else {
            Feedback::addNegative('Failed! Unknown reason.');
            return false;                
        }
    }

    /**
     * User edit process.
     * @return bool success state
     */
    public function editSave()
    {
        //get the inputs
        $id = Request::post('user_id');
        $name = Request::post('user_name');
        $email = Request::post('user_email');
        $password = Request::post('user_password');
        $age = (int)Request::post('user_age');
        $sex = Request::post('user_sex');
        $qual = Request::post('user_qual');
        $bio = Request::post('user_bio');
        $phone = Request::post('user_phone');
        $mobile = Request::post('user_mobile');
        $address = Request::post('user_address');
        $mode = Request::post('user_course_mode');
        
        //since some of the next are checkbox/option, process those inputs.
        $anon = (Request::post('user_anon') == 'yes') ? YES : NO;
        $type = Request::post('user_type');
        $approved = (Request::post('user_approved') == 'yes') ? YES : NO;
        $active = (Request::post('user_active') == 'yes') ? YES: NO;   


        $hash; //variable to hold the hash if needed

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

        //check if the User is in use(taking a course/teaching a student) if there is a change in the type
        if ($type <> User::getUserType($id)) {
            if (User::isUserInUse($id)) {
                Feedback::addNegative('Failed! user is in use, cannot change type');
                return false;
            }
        }

        //OK try to add to db     
        //is there a password field?   
        if (!$password || strlen($password) == 0) { //NO
            $success = User::update($id, $name, $email, $age, $sex, $qual, $bio, $phone, 
            $mobile, $address, $mode, $type, $approved, $active, $anon); 
        } 
        else { //new password provided. Validate it
            
            $password = trim($password);            
            if(strlen($password) == 0 || strlen($password) > 8) {
                Feedback::addNegative('Failed! user password is invalid.');
                return false;
            }
            //Note: this is a PHP 5.5.5+ function, but we use it with a compatibility lib
            $hash = password_hash($password, PASSWORD_DEFAULT);  
            
            //try updating user          
            $success = User::update($id, $name, $email, $age, $sex, $qual, $bio, $phone, 
            $mobile, $address, $mode, $type, $approved, $active, $anon); 

            //try updating password
            $pass_success = User::updatePassword($id, $hash);
            
            //notify the user about password update
            if ($pass_success) {
                Feedback::addPositive('Password updated');
            }
            else {
                Feedback::addNegative('Failed to update password');
            }
        }

        //has it got updated? if so success.
        if ($success) {
            Feedback::addPositive("Success! user '{$name}' updated.");
            return true;
        }  

        //We come here if its not updated properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;         
    }
 
    /**
     * User delete process.
     * @return bool success state
     */
    public function deleteSave(){
        //get the inputs
        $id = Request::post('user_id');

        //validate it
        if(!$id) {
            //hacking attempt? why should we reach here without an id?
            Feedback::addNegative ('Failure! No user to delete.');
            return false;
        }

        //check if the User is in use(taking a course/teaching a student)
        if (User::isUserInUse($id)) {
            Feedback::addNegative('Failed! user is in use, cannot delete');
            return false;
        }        

        //ok, lets try to delete
        $success = User::delete($id);

        //has it got deleted?
        if ($success) {
            Feedback::addPositive("Success! user (ID={$id}) deleted.");
            return true;
        }        

        //We come here if its not deleted properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;
    }    

    /**
     * User's change password process.
     * @return bool success state
     */
    public function changePasswordSave()
    {
        //get the inputs
        $id = Session::get('user_id');
        $old_pass = Request::post('user_old_password');
        $new_pass = Request::post('user_new_password');

        $user = User::getInstance($id);

        //is the old password correct? if not exit
        if (!password_verify($old_pass, $user->getPasswordHash()))
        {
            Feedback::addNegative("Failed! Old password is wrong.");
            return false;           
        }                   

        //Validate new password
        $new_pass = trim($new_pass);            
        if(strlen($new_pass) == 0 || strlen($new_pass) > 8) {
            Feedback::addNegative('Failed! New password is invalid.');
            return false;
        }  

        //Note: this is a PHP 5.5.5+ function, but we use it with a compatibility lib
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);   
        $success = User::updatePassword($id, $hash);
            
        //notify the user about password update and return
        if ($success) {
            Feedback::addPositive('Password updated');
            return true;
        }

        Feedback::addNegative('Failed to update password');
        return false;          
    } 
}
