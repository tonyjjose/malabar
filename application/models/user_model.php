<?php

/**
 * UserModel
 *
 * Handles the users stuff in db
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

    public function addSave()
    {
        //get the inputs
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
        
        //since it is a checkbox it wont be set if it was not checked by user.
        $anon = (Request::post('user_anon') == 'yes') ? YES : NO;

        //these will be set only by manager, so set default values if they are unset
        $type = Request::post('user_type');
        $type = (is_null($type)) ? ROLE_STUDENT : $type;
        $approved = Request::post('user_approved');
        $approved = (is_null($approved)) ? NO : ($approved == 'yes') ? YES : NO;
        $active = Request::post('user_active');
        if (is_null($active)){ $active = YES; }
            //it is set, so process it.
            else { $active = (($active) == 'yes') ? YES : NO; }


        //ok we have the inputs, validate them
    	if(!$name || strlen($name)== 0 || strlen($name) > 64) {
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
        if(!$password || strlen($password)== 0 || strlen($password) > 8) {
            Feedback::addNegative('Failed! user password is invalid.');
            return false;
        }     
        if($age < 14 || $age > 99) {
            Feedback::addNegative('Failed! user should be older than 14.');
            return false;
        }  

        //Note: this is a PHP 5.5.5+ function, but we use it with a compatibility lib
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //create the time string to be put in DB.
        $create_time = date('Y-m-d H:i:s', time());

    	//ok, try to add to db
    	$sql = "INSERT INTO users (user_name, user_password_hash, user_email, user_age, user_sex, user_qualification,
            user_bio, user_phone, user_mobile, user_address, user_course_mode, user_type, user_approved, user_active,
            user_anonymous, user_creation_timestamp) 
            VALUES (:name,:hash,:email,:age,:sex,:qual,:bio,:phone,:mobile,:address,:mode,:type,:approved,:active,
            :anon,:creation)";
    	$query = $this->db->prepare($sql);
    	$query->execute(array(':name'=>$name,':hash'=>$hash,':email'=>$email,':age'=>$age,':sex'=>$sex,':qual'=>$qual,':bio'=>$bio,':phone'=>$phone,':mobile'=>$mobile,':address'=>$address,':mode'=>$mode,':type'=>$type,':approved'=>$approved,
            ':active'=>$active,':anon'=>$anon,':creation'=>$create_time));

    	//has it got added? if so success.
    	if ($query->rowCount() == 1) {
    		Feedback::addPositive("Success! user '{$name}' added.");
            return true;
        }  

    	//We come here if its not added properly, notify it and exit
    	Feedback::addNegative('Failed! Unknown reason.');
    	return false;
    }

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
        
        //since it is a checkbox it wont be set if it was not checked by user.
        $anon = (Request::post('user_anon') == 'yes') ? YES : NO;

        //these will be set only by manager, so set default values if they are unset
        $type = Request::post('user_type');
        $type = (is_null($type)) ? ROLE_STUDENT : $type;
        $approved = Request::post('user_approved');
        $approved = (is_null($approved)) ? NO : ($approved == 'yes') ? YES : NO;
        $active = Request::post('user_active');
        if (is_null($active)){ $active = YES; }
            //it is set, so process it.
            else { $active = (($active) == 'yes') ? YES : NO; }

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
        if(!$password || strlen(trim($password)) == 0 || strlen($password) > 8) {
            Feedback::addNegative('Failed! user password is invalid.');
            return false;
        }  
        $password = trim($password);

        if($age < 14 || $age > 99) {
            Feedback::addNegative('Failed! user should be older than 14.');
            return false;
        }  

        //is there a password field? if so get new hash and include it in query
        if (!$password || strlen(trim($password)) == 0) {
            $sql = "UPDATE users SET user_name = :name, user_email = :email, user_age = :age, user_sex = :sex,
                user_qualification = :qual, user_bio = :bio, user_phone = :phone, user_mobile = :mobile,
                user_address = :address, user_course_mode = :mode, user_type = :type, user_approved = :approved,
                user_active = :active, user_anonymous = :anon WHERE user_id =:id";
        }
        else {
            //Note: this is a PHP 5.5.5+ function, but we use it with a compatibility lib
            $hash = password_hash(trim($password), PASSWORD_DEFAULT);
            $sql = "UPDATE users SET user_name = :name, user_email = :email, user_age = :age, user_sex = :sex,
                user_qualification = :qual, user_bio = :bio, user_phone = :phone, user_mobile = :mobile,
                user_address = :address, user_course_mode = :mode, user_type = :type, user_approved = :approved,
                user_active = :active, user_anonymous = :anon, user_password_hash = :hash WHERE user_id =:id";   
        }


        //ok, try to add to db
        $query = $this->db->prepare($sql);
        $query->execute(array(':name'=>$name,':hash'=>$hash,':email'=>$email,':age'=>$age,':sex'=>$sex,':qual'=>$qual,':bio'=>$bio,':phone'=>$phone,':mobile'=>$mobile,':address'=>$address,':mode'=>$mode,':type'=>$type,':approved'=>$approved,
            ':active'=>$active,':anon'=>$anon,':id'=>$id));

        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            Feedback::addPositive("Success! user '{$name}' updated.");
            return true;
        }  

        //We come here if its not added properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;

    }    

    public function deleteSave(){
        //get the inputs
        $id = Request::post('user_id');

        //validate it
        if(!$id) {
            //hacking attempt? why should we reach here without an id?
            Feedback::addNegative ('Failure! No user to delete.');
            return false;
        }

        //ok, lets try to delete
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id'=>$id));

        //has it got deleted?
        if ($query->rowCount() == 1) {
            Feedback::addPositive("Success! user (ID={$id}) deleted.");
            return true;
        }        

        //We come here if its not deleted properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;
    }    

    public function saveCategory(){

    	//get the inputs
    	$name = Request::post('category_name');
    	$desc = Request::post('category_desc');

    	//validate them
    	if(!$name || strlen($name)== 0 || strlen($name) > 15) {
    		Feedback::addNegative('Failed! user Category name is invalid.');
    		return false;
    	}

    	//Check if the category name already exist
    	if (Category::categoryExists($name)) {
    		Feedback::addNegative("Failure! user Category name already exists.");
    		return false;
    	}

    	//ok, try to add to db
    	$sql = "INSERT INTO category (cat_name, cat_desc) VALUES (:name, :desc)";
    	$query = $this->db->prepare($sql);
    	$query->execute(array(':name'=>$name,':desc'=>$desc));

    	//has it got added? if so success.
    	if ($query->rowCount() == 1) {
    		Feedback::addPositive("Success! user category '{$name}' added.");
            return true;
        }  

    	//We come here if its not added properly, notify it and exit
    	Feedback::addNegative("Failed! Unknown reason.");
    	return false;

    }

}
