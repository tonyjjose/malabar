<?php

/**
 * UserObject base class
 *
 * This is the user object. It provides static methods for DB operations as well us helper methods
 * for various view purposes. Other specialised types like Student, Manager and Instructor extend this class
 */


class User
{
    //properties
    private $id = 0;
    private $name;
    private $passward_hash;
    private $email;
    private $age = 0;
    private $sex;
    private $qual;
    private $bio;
    private $phone;
    private $mobile;
    private $address;
    private $course_mode;
    private $type = ROLE_NONE; //lets give default role
    private $approved = false;
    private $active = true;
    private $anon = false;
    private $created = 0;
    private $last_login = 0;

    //getter and setter
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getPasswordHash(){
        return $this->passward_hash;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getAge(){
        return $this->age;
    }
    public function getSex(){
        return $this->sex;
    }
    public function getQualification(){
        return $this->qual;
    }
    public function getBio(){
        return $this->bio;
    }
    public function getPhone(){
        return $this->phone;
    }
    public function getMobile(){
        return $this->mobile;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getCourseMode(){
        return $this->course_mode;
    }
    public function getType(){
        return $this->type;
    }
    public function getApproved(){
        return $this->approved;
    }
    public function getActive(){
        return $this->active;
    }    
    public function getAnonymous(){
        return $this->anon;
    }    
    public function getCreated(){
        //lets return a formated time string, eg: Mar 11 2015, 8:01 PM
        return date("M j Y, g:i A", $this->created);
    }
    public function getLastLogin(){
        return date("M j Y, g:i A", $this->last_login);
    }
    //constructor
    function __construct($id, $name, $passward_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, $type, $approved, $active, $anon, $created, $last_login)
    {  
        $this->id = (is_int($id)) ? $id : (int)$id ;
        $this->name = $name;
        $this->passward_hash = $passward_hash;
        $this->email = $email;
        $this->age =  (is_int($age)) ? $age : (int)$age ;
        $this->sex = $sex;
        $this->qual = $qual;
        $this->bio = $bio;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->address = $address;
        $this->course_mode = $course_mode;
        $this->type = $type;
        $this->created = strtotime($created);
        $this->last_login = strtotime($last_login);

        $this->approved = (is_bool($approved)) ? $approved : filter_var($approved, FILTER_VALIDATE_BOOLEAN);
        $this->active = (is_bool($active)) ? $active : filter_var($active, FILTER_VALIDATE_BOOLEAN);
        $this->anon = (is_bool($anon)) ? $anon : filter_var($anon, FILTER_VALIDATE_BOOLEAN);
    } 

    /**
     * Update users login timestamp.
     * 
     * This is done during every successful login
     * @return bool success state
     */
    public function saveLoginTimeStamp()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //create the time string to be put in DB.
        $timeStamp =  date('Y-m-d H:i:s', time());

        $query = $db->prepare("UPDATE users SET user_last_login_timestamp = :ts WHERE user_id = :id");
        $query->execute(array(':ts' => $timeStamp,':id' => $this->id));

        //has it got updated? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;        
    }

    /**
     * Create User instance.
     *
     * We return the specialised classes depending on the type of the user. 
     * @return object user or null
     */
    public static function getInstance($id){

        $db = DatabaseFactory::getFactory()->getConnection();

        //query the DB
        $query = $db->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
        $query->execute(array(':id' => $id));
        $row = $query->fetch(); 

        //if nothing return null 
        if (empty($row)) {return null;}

        if ($row->user_type == 'M') {
            return new Manager($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,$row->user_address,
                $row->user_approved,$row->user_active,$row->user_anonymous,
                $row->user_creation_timestamp,$row->user_last_login_timestamp);
        }
        elseif ($row->user_type == "I") {
            return new Instructor($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,$row->user_address,
                $row->user_approved,$row->user_active,$row->user_anonymous,
                $row->user_creation_timestamp,$row->user_last_login_timestamp);            
        }
        elseif ($row->user_type == 'S') {
            return new Student($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,$row->user_address,
                $row->user_course_mode,$row->user_approved,$row->user_active,$row->user_anonymous,
                $row->user_creation_timestamp,$row->user_last_login_timestamp);
        }
        else
        {
            return new User($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,$row->user_address,
                $row->user_course_mode,$row->user_type,$row->user_approved,$row->user_active,$row->user_anonymous,
                $row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

       /* return new User($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
            $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,$row->user_address,
            $row->user_course_mode,$row->user_type,$row->user_approved,$row->user_active,$row->user_anonymous,
            $row->user_creation_timestamp,$row->user_last_login_timestamp);     */ 
    }

    /**
     * Create User instance from email.
     *
     * We query for the ID and call the getInstance method. 
     * @return object user or null
     */    
    public static function getInstanceFromEmail($email)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //Query the DB
        $query = $db->prepare("SELECT user_id FROM users WHERE user_email = :email LIMIT 1");
        $query->execute(array(':email' => $email));
        $row = $query->fetch();

        //if nothing return null 
        if (empty($row)) {return null;}   

        return User::getInstance($row->user_id);

    }    

    /**
     * Create User instance from email.
     *
     * We query for the ID and call the getInstance method. 
     * @return object user or null
     */    
    public static function getIdFromEmail($email)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //Query the DB
        $query = $db->prepare("SELECT user_id FROM users WHERE user_email = :email LIMIT 1");
        $query->execute(array(':email' => $email));
        $row = $query->fetch();

        //if nothing return null 
        if (empty($row)) {return null;}   

        return $row->user_id;

    } 
    /**
     * Create all user objects array
     * @return array[] of object user or null
     */
    public static function getAllUsers()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM users ORDER BY user_name, user_type ASC");   
        $rows = $query->fetchAll();

        //the list of objects
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode,$row->user_type,$row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $users;       
    }

    public static function getLatestUsers()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->query("SELECT * FROM users ORDER BY user_creation_timestamp DESC LIMIT 10");   
        $rows = $query->fetchAll();

        //the list of objects
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User($row->user_id,$row->user_name,$row->user_password_hash,$row->user_email,$row->user_age,
                $row->user_sex,$row->user_qualification,$row->user_bio,$row->user_phone,$row->user_mobile,
                $row->user_address,$row->user_course_mode,$row->user_type,$row->user_approved,$row->user_active,
                $row->user_anonymous,$row->user_creation_timestamp,$row->user_last_login_timestamp);
        }

        return $users;         
    }

    /**
     * Save user to DB.
     * 
     * Users last login is not added while saving
     * @return bool success state
     */
    public static function save($name, $password_hash, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, $type, $approved, $active, $anon, $created)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //ok, try to add to db
        $sql = "INSERT INTO users (user_name, user_password_hash, user_email, user_age, user_sex, user_qualification,
            user_bio, user_phone, user_mobile, user_address, user_course_mode, user_type, user_approved, user_active,
            user_anonymous, user_creation_timestamp) 
            VALUES (:name,:hash,:email,:age,:sex,:qual,:bio,:phone,:mobile,:address,:mode,:type,:approved,:active,
            :anon,:creation)";
        $query = $db->prepare($sql);
        $query->execute(array(':name'=>$name,':hash'=>$password_hash,':email'=>$email,':age'=>$age,':sex'=>$sex,':qual'=>$qual,':bio'=>$bio,':phone'=>$phone,':mobile'=>$mobile,':address'=>$address,':mode'=>$course_mode,':type'=>$type,':approved'=>$approved,
            ':active'=>$active,':anon'=>$anon,':creation'=>$created));  

        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Update user to DB.
     * 
     * User create time, or last login is not updated, also We do not update password here 
     * but use a separate method for that
     * @return bool success state
     */
    public static function update($id, $name, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $course_mode, $type, $approved, $active, $anon)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET user_name = :name, user_email = :email, user_age = :age, user_sex = :sex,
            user_qualification = :qual, user_bio = :bio, user_phone = :phone, user_mobile = :mobile,
            user_address = :address, user_course_mode = :mode, user_type = :type, user_approved = :approved,
            user_active = :active, user_anonymous = :anon WHERE user_id =:id";
        $query = $db->prepare($sql);
        $query->execute(array(':name'=>$name,':email'=>$email,':age'=>$age,':sex'=>$sex,':qual'=>$qual,':bio'=>$bio,
            ':phone'=>$phone,':mobile'=>$mobile,':address'=>$address,':mode'=>$course_mode,':type'=>$type,':approved'=>$approved,
            ':active'=>$active,':anon'=>$anon,':id'=>$id));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false; 
    }

    /**
     * Delete user from DB.
     * 
     * @return bool success state
     */
    public static function delete($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //ok, lets try to delete
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $query = $db->prepare($sql);
        $query->execute(array(':user_id'=>$id));
        
        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Update user's password to DB.
     * 
     * @return bool success state
     */
    public static function updatePassword($id, $password_hash)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->prepare("UPDATE users SET user_password_hash = :hash WHERE user_id = :id");
        $query->execute(array(':hash' => $password_hash,':id' => $id));

        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;           
    }

    /**
     * Check for user email uniqueness
     *
     * This is used while adding a new user name. User emails must be unique
     * @param @name, @id Email
     * @return bool whether email already exits
     */

    public static function emailExists($email)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //Query the DB
        $query = $db->prepare("SELECT user_id FROM users WHERE user_email = :email LIMIT 1");
        $query->execute(array(':email' => $email));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;        
    }

    /**
     * Check if a given user email already exists for another user.
     *
     * This is used while editing a user's.
     * @param @email, @id Email and ID of the current user
     * @return bool whether user email exists for another user
     */    
    public static function emailExistsForAnotherUser($email, $id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        //Query the DB
        $query = $db->prepare("SELECT user_id FROM users WHERE user_email = :email AND user_id <> :id LIMIT 1");
        $query->execute(array(':email' => $email,':id' => $id));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;        
    }   

    /**
     * Retrieve the user's type
     *
     * @param @id ID of the  user
     * @return string User type
     */
    public static function getUserType($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->prepare("SELECT user_type FROM users WHERE user_id = :id");
        $query->execute(array(':id' => $id));        

        //has it got added? if so success.
        if ($query->rowCount() == 1) {
            return $query->fetch()->user_type;
        }
    }

    /**
     * Check if a user is taking/teaching a course.
     * 
     * This check is maily used when changing the type of a user. If user is in use, changing his type 
     * will make the DB inconsistent
     * @return bool use state
     */
    public static function isUserInUse($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $query = $db->prepare("SELECT student_id FROM student_course WHERE student_id = :id OR instructor_id = :id LIMIT 1");   
        $query->execute(array(':id'=>$id));

        return ($query->rowCount() == 1);          
    }     
}
