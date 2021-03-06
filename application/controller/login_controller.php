<?php

/**
 * Class LoginController
 * This is a login controller which handles the login..
 * We use the URLs ..app/login/index, app/login/signin, app/login/signout etc
 *
 *
 */
class LoginController extends controller
{
    function __construct()
    {  
       parent::__construct();  	
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to ..app/login/index. 
     * We use this to show the login form.
     */
    public function index(){
  
        //Show the login page
        //we need to pass feedback as we return to same page if we have wrong password. 
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );
        $this->view->render('login/index.html.twig',$params);	
    }

        /**
        * Check the supplied credentionals and validate the user.
        * 
        * This page will be the action [target] of the login form and this handles the POST data to 
        * validate the user.
        */
    public function signIn () {

        // run the login() method in the login-model
        $login_model = $this->loadModel('Login');
        // perform the login method, put result (true or false) into $login_successful
        $login_successful = $login_model->signIn();

        // check login status
        if ($login_successful) {
            // if YES, then move user to home)
            Redirect::home();
        } else {
            // if NO, then move user to login/index (login form) again
            Redirect::to('login');
        }
    }        

    /**
     * The logout action
     * 
     * Just hit this and you will be signed out. 
     */       
    public function signOut()
    {
        $login_model = $this->loadModel('Login');
        $login_model->signOut();
        // redirect user to base URL
        header('location: ' . URL);
    }

}
