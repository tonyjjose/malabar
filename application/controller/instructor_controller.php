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
     * We display a short profile information of the instructor. His courses, assignments, and links to various
     * actions that he can perform.
     */
    public function index()
    {
        $id = Session::get('user_id');

        $instructor = Instructor::getInstance($id);
        $myCourses = Instructor::getMyCourses($id);
        $assignments = Instructor::getLatestAssignments($id);
        $params = array('user'=>$instructor, 'courses'=>$myCourses,'assignments'=>$assignments);
        $this->view->render('instructor/index.html.twig',$params);	
    }

    /**
     * Display his list of students for a particular course
     */    
    public function showStudents($course_id)
    {
        $id = Session::get('user_id');  
        
        $students = Instructor::getMyCourseStudents($id, $course_id);
        $course = Course::getInstance($course_id);
        
        $params = array('course'=>$course, 'students'=>$students);
        $this->view->render('instructor/studentlist.html.twig',$params);  
    }

    /**
     * Display his list of all assignments
     */
    public function assignments()
    {
        $id = Session::get('user_id');        
        $assignments = Instructor::getAllAssignments($id);

        $params = array('assignments'=>$assignments);
        $this->view->render('instructor/assignments.html.twig',$params); 

    } 
}