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

    public function test () {
        $session_id = Session::get('user_id');        
        Student::isStudentMyCourseMate($session_id, 23);

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

        //lets set the showdetails flag to false
        $show_details = false;

        if (!$id) 
        {
            //No $id given, so lets display the user's profile
            $id = $session_id;
            //the user can see his full details
            $show_details = true;
        } 
        elseif (Student::isUserStudent($session_id)) 
        {
            //We have an $id param, and the session user is a student.
            if(Instructor::isUserInstructor($id))
            {
                //the $id is instructor, so show his full details
                $show_details = true;
            } 
            elseif (Student::isStudentMyCourseMate($session_id,$id)) 
            {
                //the $id is my coursemate, so we can view him, but not his full details
                $show_details = false;
            }
            else 
            {
                //the $id is not an instructor nor our coursemate, so we cant view.
                Redirect::to('error/noauth');
            }
        }
        else 
        {
            //this must be the manager or instructor, let him see details.
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

