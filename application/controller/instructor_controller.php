<?php

/**
 * InstructorController class
 * 
 * This handles all the instructor related requests like, URL/instructor/...
 *
 */
class InstructorController extends controller
{
    function __construct()
    {  
       parent::__construct();

       if(!(Session::get('user_type') == ROLE_INSTRUCTOR)) {
            Redirect::to('error/noauth');
       }
    }

    /**
     * Display the instructor's dashboad.
     * 
     * We display a short profile information of the instructor. His courses, and links to various
     * actions that he can perform.
     */
    public function index()
    {
        $id = Session::get('user_id');

        $instructor = Instructor::getInstance($id);
        $myCourses = Instructor::getMyCourses($id);
        $params = array('user'=>$instructor, 'courses'=>$myCourses);
        $this->view->render('instructor/index.html.twig',$params);	
    }
    public function showStudents($instructor_id, $course_id){
  
        
        $students = Instructor::getMyCourseStudents($instructor_id, $course_id);
        $course = Course::getInstance($course_id);
        
        $params = array('course'=>$course, 'students'=>$students );
        $this->view->render('instructor/studentlist.html.twig',$params);  
    }    
}