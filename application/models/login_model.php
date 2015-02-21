<?php

/**
 * LoginModel
 *
 * Handles the user's login / logout 
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
        // See if the data is send, if not, give feedback and exit false
        if (!isset($_POST['user_name']) OR empty($_POST['user_name'])) {
            Feedback::addNegative(FEEDBACK_USERNAME_FIELD_EMPTY);
            return false;
        }
        if (!isset($_POST['user_password']) OR empty($_POST['user_password'])) {
            Feedback::addNegative(FEEDBACK_PASSWORD_FIELD_EMPTY);           
            return false;
        }

        //As of now we make the user logged in. later we add the authentication code here
        //Session::init();
        Session::set('user_logged_in', true);    
        return true;    

    }

    /**
     * Returns the current state of the user's login
     * @return bool user's login status
     */
    public function isUserLoggedIn()
    {
        return Session::get('user_logged_in');
    }  
    
    /**
     * Logout process.    
     */
    public function signOut()
    {
        // delete the session
        Session::destroy();
    }      
}