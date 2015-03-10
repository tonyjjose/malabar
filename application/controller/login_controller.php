<?php

/**
 * LoginController class.
 *
 * This handles all the login related requests..
 * We use the URLs app/login/index, app/login/signin, app/login/signout etc
 */
class LoginController extends controller
{
    
    function __construct()
    {  
       parent::__construct();  	
    }

    /**
     * A test function for debugging purposses.
     *
     * We can use this for outputting vars, testing code and so and so. This is accessible for non-authenticated
     * users
     */
    public function test()
    {
        //var_dump($_SESSION);
    }

    /**
     * Display login form
     */
    public function index()
    {  
        //Show the login page
        $this->view->render('login/index.html.twig');	
    }

    /**
     * Display the registration from
     *
     */
    public function register()
    {
        //authenticated users need not come here again.
        $login_model = $this->loadModel('Login');
        if ($login_model->isUserLoggedIn()) {
            Feedback::addNegative('You are already registered.');
            Redirect::home();
        }    

        //show the form 
        $this->view->render('login/register.html.twig');   
    }

    /**
     * Logout the user
     *  
     */       
    public function signOut()
    {
        $login_model = $this->loadModel('Login');
        $login_model->signOut();
        
        // redirect user to the public website
        header('location: ' . WWW);
    }    
    
    /**
     * Display the change password from
     *
     */
    public function changePassword()
    {
        //non authenticated users need not touch here.
        $login_model = $this->loadModel('Login');
        if (!$login_model->isUserLoggedIn()) {
            Redirect::to('login');
        }
        
        //show the form
        $this->view->render('login/changepassword.html.twig');   
    }    

    /**
    * POST request handler for login form
    *
    * Check the supplied credentionals and validate the user.
    * Upon validation redirect to the user's home page. 
    */
    public function signIn () 
    {
        //load the login model and run the signIn() method
        $login_model = $this->loadModel('Login');
        $login_successful = $login_model->signIn();

        // check login status
        if ($login_successful) {
            // if YES, then move user to home
            Redirect::home();
        } else {
            // if NO, then move user to login/index (login form) again
            Redirect::to('login');
        }
    }  

    /**
     * POST request handler for change password form
     *  
     * Note that we use the user model and does the job.
     */ 
    public function changePasswordSave()
    {
        $user_model = $this->loadModel('User');
        $change_success = $user_model->changePasswordSave();

        if ($change_success) {
            Redirect::home();
        } else {
            //let him try again
            Redirect::to('login/changepassword');
        }

    }   

    /**
     * POST request handler for registration form
     * 
     * We use the user model and does the job.
     */ 
    public function registerSave () {

        $user_model = $this->loadModel('User');
        $registration_success = $user_model->addSave();

        if ($registration_success) {
            // if YES, then display confirmation and inform about approval process.
            Redirect::to('error/approval');
        } else {
            // if NO, then move user to registration form again
            Redirect::to('login/register');
        }
    }        
}
