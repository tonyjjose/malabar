<?php

/**
 * Class CourseController
 * 
 * Handles all course related stuff
 *
 */
class CourseController extends Controller
{
    /**
     * PAGE: index
     * We will list all the available courses here. 
     * For managers there will be add/edit/delete controls.
     */
    public function index()
    {
        //as of now display the home, later we will redirect to respective pages based on user roles.
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );        
        $this->view->render('course/index.html.twig', $params);

    }

    /**
     * Add Course page.
     * We display the form, along with the feedback of any previous actions.
     *
     */  
    public function add()
    {
        $categoryNames = Course::getAllCourseCategoryNames();
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive()
            ,'category_names'=>$categoryNames );        
        $this->view->render('course/add.html.twig', $params);        

    }
    /**
     * POST request after add course form submitted.
     * We call the model and save it.
     *
     */  
    public function addSave()
    {
        $course_model = $this->loadModel('Course');
        $success = $course_model->addSave(); //we dont use $success now.  
        Redirect::to('course/add'); 
    }


    /**
     * Edit Course page.
     * We display the edit form, along with the feedback of any previous actions.
     *
     */  
    public function edit($id)
    {

    }
    /**
     * EditSave POST request after edit page.
     * Save the edits
     *
     */  
    public function editSave($id)
    {

    }  


    /**
     * Delete Course page.
     * We can display a confirmation dialog here.
     *
     */  
    public function delete($id)
    {
        //is there anything to delete?
        if(!$id) {
            Redirect::to('error');
        }

        //ask for confirmation
        $courseName = Course::getCourseName($id);

        $params = array('course_name'=>$courseName,'course_id'=>$id);
        $this->view->render('course/delete.html.twig', $params);        


    }   
      
    /**
     * Delete Course POST request
     * Delete it
     *
     */  
    public function deleteSave()
    {
        echo 'we came ehre' . Request::post('course_id').'';
        $course_model = $this->loadModel('Course');
        $success = $course_model->deleteSave();
        Redirect::to('course');  
    }  


    /**
     * Add Course category page.
     * We display the form, along with the feedback of any previous actions.
     *
     */    
    public function addCategory()
    {
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );        
        $this->view->render('course/addCategory.html.twig', $params);
    }

    /**
     * The is the where the form data (POST) is handled
     * We use the course model to validate the data and save to db
     * Finally we redirect back to the add category page.
     */

    public function addCategorySave()
    {
        $course_model = $this->loadModel('Course');
        $success = $course_model->saveCategory(); //we dont use $success now.  
        Redirect::to('course/addcategory');   
    }
}