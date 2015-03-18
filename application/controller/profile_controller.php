<?php

/**
 * ProfileController class.
 *
 * This handles all the profile related requests like, URL/profile/...
 */

class ProfileController extends Controller
{
    /**
     * Call the base constructor and also check for authorisation.
     */
    function __construct()
    {  
       parent::__construct();
    }

    /**
     * Redirect to view.
     */
    public function index()
    {
    	Redirect::to('profile/view');
    }

    /**
     * Display the users profile.
     *
     * When no ID given the logged in user's profile is shown. Otherwise the other users 
     * profile is shown.
     */
    public function view($id=null)
    {
    	$session_id = Session::get('user_id');

    	//if no ID is given then display the user's profile
        if(!$id) { $id = $session_id; } 

        
        $show_details = ($id == $session_id) ? true : false;

        if (!(Student::isUserStudent($session_id))) {
            $show_details = true;
        }

        if (Instructor::isUserInstructor($id)) {
            $show_details = true;
        }     

    	$user = User::getInstance($id);
        $params = array('user'=>$user, 'show_details'=>$show_details);
        $this->view->render('profile/view.html.twig',$params);	
    }

    public function edit()
    {
    	$id = Session::get('user_id');

        $user = User::getInstance($id);
        $params = array('user'=>$user );            
        $this->view->render('profile/edit.html.twig',$params);

    }

    public function editSave()
    {
    	Redirect::to("profile/view");
    }    
    
    /**
     * Display the change password from
     *
     */
    public function changePassword()
    {
        //show the form
        $this->view->render('profile/changepassword.html.twig');   
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
            Redirect::to('profile/changepassword');
        }

    }
}

