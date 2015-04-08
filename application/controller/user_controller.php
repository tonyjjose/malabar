<?php

/**
 * UserController class
 * 
 * This handles all the user related requests like, URL/user/...
 *
 */
class UserController extends Controller
{
    /**
     * Call the base constructor and also check for authorisation.
     * 
     * Should be only accessible by a Manager
     */    
    function __construct()
    {  
       parent::__construct();

       //only managers can access this controller
       if(!(Session::get('user_type') == ROLE_MANAGER)) {
            Redirect::to('error/noauth');
       }
    } 
    
    /**
     * Users main page.
     *
     * We will list all the available users here. And links for add/editing/deleting a user.
     */
    public function index()
    {
        //gather the parameters
        $users = User::getAllUsers();       
        $params = array('users'=>$users);  

        $this->view->render('user/index.html.twig', $params);
    }

    /**
     * Display add user form.
     */  
    public function add()
    {
        $this->view->render('user/add.html.twig');        
    }

    /**
     * POST request handler for add user form.
     */ 
    public function addSave()
    {
        $user_model = $this->loadModel('User');
        $success = $user_model->addSave();  
        
        if ($success) {
            Redirect::to('user');
        } else {
            Redirect::to('user/add');
        }
    }

    /**
     * Display edit user form.
     */  
    public function edit($id)
    {
        //is there anything to edit?
        if(!$id) {
            Redirect::to('error');
        }  

        //gather the parameters
        $user = User::getInstance($id);       

        $params = array('user'=>$user);
        $this->view->render('user/edit.html.twig', $params); 
    }

    /**
     * POST request handler for edit user form.
     */ 
    public function editSave()
    {
        $id =  Request::post('user_id');

        $user_model = $this->loadModel('User');
        $success = $user_model->editSave();
        
        if ($success) {
            Redirect::to('user'); 
        } else {
            Redirect::to("user/edit/{$id}");
        }
    }  

    /**
     * Display delete user confirmation page.
     */  
    public function delete($id)
    {
        //is there anything to delete?
        if(!$id) {
            Redirect::to('error');
        }

        //gather the parameters
        $user = User::getInstance($id);

        $params = array('user'=>$user);
        $this->view->render('user/delete.html.twig', $params);        
    }   

    /**
     * POST request handler for delete user form.
     */ 
    public function deleteSave()
    {
        $user_model = $this->loadModel('User');
        $success = $user_model->deleteSave();
        Redirect::to('user');  
    } 
}