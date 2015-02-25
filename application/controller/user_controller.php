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
        //
        $users = User::getAllusers();
        $categories = User::getAllUserCategoryNames();        
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
            'users'=>$users, 'categories'=>$categories );        
        $this->view->render('User/index.html.twig', $params);

    }

    /**
     * Add User page.
     * We display the form, along with the feedback of any previous actions.
     *
     */  
    public function add()
    {
        //gather the parameters        
        $categoryNames = User::getAllUserCategoryNames();
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive()
            ,'category_names'=>$categoryNames );        
        $this->view->render('User/add.html.twig', $params);        

    }
    /**
     * POST request after add User form submitted.
     * We call the model and save it.
     *
     */  
    public function addSave()
    {
        $User_model = $this->loadModel('User');
        $success = $User_model->addSave(); //we dont use $success now.  
        Redirect::to('User/add'); 
    }


    /**
     * Edit User page.
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
        $User = User::getUser($id);
        $categories = User::getAllUserCategoryNames();        

        $params = array('User'=>$User,'categories'=>$categories);
        $this->view->render('User/edit.html.twig', $params); 
    }
    /**
     * EditSave POST request after edit page.
     * Save the edits
     *
     */  
    public function editSave()
    {
        $User_model = $this->loadModel('User');
        $success = $User_model->editSave(); //we dont use $success now.  
        Redirect::to('User'); 
    }  


    /**
     * Delete User page.
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
        $UserName = User::getUserName($id);

        //display request for confirmation 
        $params = array('User_name'=>$UserName,'User_id'=>$id);
        $this->view->render('User/delete.html.twig', $params);        


    }   
      
    /**
     * Delete User POST request
     * Delete it
     *
     */  
    public function deleteSave()
    {
        echo 'we came ehre' . Request::post('User_id').'';
        $User_model = $this->loadModel('User');
        $success = $User_model->deleteSave();
        Redirect::to('User');  
    }  