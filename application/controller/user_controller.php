<?php

/**
 * Class UserController
 * 
 * Handles all user related stuff
 *
 */
class UserController extends Controller
{

    /**
     * PAGE: index
     * We will list all the available users here. 
     * For managers there will be add/edit/delete controls.
     */
    public function index()
    {
        //gather the parameters
        $users = User::getAllUsers();       
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
            'users'=>$users,);        
        $this->view->render('user/index.html.twig', $params);

    }

    /**
     * Add user page.
     * We display the form, along with the feedback of any previous actions.
     *
     */  
    public function add()
    {
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),'showManagerControls'=>false);        
        $this->view->render('user/add.html.twig', $params);        

    }
    /**
     * POST request after add user form submitted.
     * We call the model and save it.
     *
     */  
    public function addSave()
    {
        $user_model = $this->loadModel('User');
        $success = $user_model->addSave(); //we dont use $success now.  
        Redirect::to('user/add');
    }


    /**
     * Edit user page.
     * We display the edit form, along with the feedback of any previous actions.
     *
     */  
    public function edit($id)
    {
        //is there anything to edit?
        if(!$id) {
            Redirect::to('error');
        }  

        //gather the parameters
        $user = User::getInstance($id);       

        $params = array('user'=>$user,);
        $this->view->render('user/edit.html.twig', $params); 
    }
    /**
     * EditSave POST request after edit page.
     * Save the edits
     *
     */  
    public function editSave()
    {
        $user_model = $this->loadModel('user');
        $success = $user_model->editSave(); //we dont use $success now.  
        Redirect::to('user');
    }  


    /**
     * Delete user page.
     * We can display a confirmation dialog here.
     *
     */  
    public function delete($id)
    {
        //is there anything to delete?
        if(!$id) {
            Redirect::to('error');
        }

        //gather the parameters
        $user = User::getInstance($id);

        //display request for confirmation 
        $params = array('user'=>$user);
        $this->view->render('user/delete.html.twig', $params);        


    }   
      
    /**
     * Delete user POST request
     * Delete it
     *
     */  
    public function deleteSave()
    {
        $user_model = $this->loadModel('user');
        $success = $user_model->deleteSave();
        Redirect::to('user');  
    } 

    public function enrol($id)
    {
        //is there anything to delete?
        if(!$id) {
            Redirect::to('error');
        }

        //gather the parameters
        $cousrses = Course::getAllCourses();
        $student = User::getInstance($id);

        //display request for confirmation 
        $params = array('courses'=>$courses,'student'=>$student);
        $this->view->render('user/enrol.html.twig', $params);        


    } 
}