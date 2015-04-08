<?php

/**
 * CourseController class.
 *
 * This handles all the course related requests like, URL/course/...
 */
class CourseController extends Controller
{
    /**
     * Call the base constructor and also check for authorisation.
     * 
     * Should be only accessible by a Manager
     */
    function __construct()
    {  
       parent::__construct();

       if(!(Session::get('user_type') == ROLE_MANAGER)) {
            Redirect::to('error/noauth');
       }
    }
    
    /**
     * Courses main page.
     *
     * We will list all the available courses here. And links for add/editing/deleting a course.
     * Course categories are also added from here.
     */
    public function index()
    {
        $courses = Course::getAllCourses();       
        $params = array('courses'=>$courses);    

        $this->view->render('course/index.html.twig', $params);
    }

    /**
     * Display Add Course page.
     *
     */  
    public function add()
    {
        //gather the parameters        
        $categories = Category::getAllCategories();
        $params = array('categories'=>$categories);

        $this->view->render('course/add.html.twig', $params);        
    }

    /**
     * POST request handler for add course form.
     * 
     * We call the model and save it.
     */  
    public function addSave()
    {
        $course_model = $this->loadModel('Course');
        $success = $course_model->addSave();   
        
        if ($success) {
            Redirect::to('course'); 
        } else {
            Redirect::to('course/add');
        }
    }

    /**
     * Display edit Course page.
     */  
    public function edit($id)
    {
        //is there anything to edit?
        if(!$id) {
            Redirect::to('error');
        }  

        //gather the parameters
        $course = Course::getInstance($id);
        $categories = Category::getAllCategories();         

        $params = array('course'=>$course,'categories'=>$categories);
        $this->view->render('course/edit.html.twig', $params); 
    }

    /**
     * POST request handler for edit course form.
     * 
     * Validate and save the edits.
     *
     */  
    public function editSave()
    {
        $id = Request::post('course_id');

        $course_model = $this->loadModel('Course');
        $success = $course_model->editSave();
        
        if ($success) {
            Redirect::to('course'); 
        } else {
            Redirect::to("course/edit/{$id}");
        }
    }  


    /**
     * Display delete course confirmation page.
     *
     */  
    public function delete($id)
    {
        //is there anything to delete?
        if(!$id) {
            Redirect::to('error');
        }

        //gather the parameters
        $course = Course::getInstance($id);

        //display request for confirmation 
        $params = array('course'=>$course);
        $this->view->render('course/delete.html.twig', $params);        
    }   
      
    /**
     * POST request handler for delete course.
     * Delete it
     */  
    public function deleteSave()
    {
        $course_model = $this->loadModel('Course');
        $course_model->deleteSave();
        Redirect::to('course');  
    }  


    /**
     * Display add course category page.
     *
     */    
    public function addCategory()
    {        
        $this->view->render('course/addCategory.html.twig');
    }

    /**
     * POST request handler for add category form.
     *
     */
    public function addCategorySave()
    {
        $course_model = $this->loadModel('Course');
        $success = $course_model->saveCategory(); //we dont use $success now.  
        
        if ($success) {
            Redirect::to('course'); 
        } else {
            Redirect::to('course/addcategory');
        }  
    }
}