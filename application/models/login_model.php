<?php

/**
 * LoginModel class
 *
 * Handles the user's login/logout related bussiness logic. 
 *
 */

class LoginModel
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
     * Login process.
     * @return bool success state
     */
    public function signIn()
    {
        //get the inputs
        $email = Request::post('user_email');
        $password = Request::post('user_password');

        // See if the data is send, if not, give feedback and exit false
        if(!$email || strlen($email) > 64 || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            Feedback::addNegative("Login failed: Invalid user email");
            return false;
        }         
        if (!$password || strlen($password) == 0) {
            Feedback::addNegative("Failed! Invalid password");           
            return false;
        }

        //ok, the inputs are fine, now lets validate it from DB
        $user = User::getInstanceFromEmail($email);

        if (is_null($user)) {
            Feedback::addNegative("Login failed: No such user");
            return false;
        }

        //is the user approved? 
        if (!$user->getApproved()) {
            Feedback::addNegative("Login failed! User not approved.");
            return false;
        }
        
        if (!password_verify($password, $user->getPasswordHash()))
        {
            Feedback::addNegative("Login failed: Wrong password");
            return false;           
        }   
        
        //looks like we are logged in, ok, save data to session
        $this->setUserDataToSession($user);

        //enter user login timestamp to db
        $user->saveLoginTimestamp();

        return true;
    }

    /**
     * Logout process.    
     */
    public function signOut()
    {
        // delete the session
        Session::destroy();
    }

    /**
     * Set authenticated user's data to session.
     * 
     */    
    private function setUserDataToSession(User $user)
    {
        Session::set('user_id', $user->getId());
        Session::set('user_name', $user->getName());
        Session::set('user_email', $user->getEmail());
        Session::set('user_type', $user->getType());
        Session::set('user_last_login', $user->getLastLogIn());
        Session::set('user_logged_in', true);         
    }

    /**
     * Returns the current state of the user's login
     * @return bool user's login status
     */
    public function isUserLoggedIn()
    {
        return Session::get('user_logged_in');
    }
}