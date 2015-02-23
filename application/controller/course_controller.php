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
     * Delete Course page.
     * We display the form, along with the feedback of any previous actions.
     *
     */  
    public function Delete($id)
    {

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