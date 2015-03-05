<?php

/**
 * Class InstructorController
 * This is a Instructor controller which handles the Instructor..
 * We use the URLs ..app/Instructor/index, app/Instructor/signin, app/Instructor/signout etc
 *
 *
 */
class InstructorController extends controller
{
    function __construct()
    {  
       parent::__construct();  	
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to ..app/Instructor/index. 
     * We use this to show the Instructor form.
     */
    public function index(){
  
        $id = 8;
        $instructor = Instructor::getInstance($id);
        $myCourses = Instructor::getMyCourses($id);
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(), 'instructor'=>$instructor, 'courses'=>$myCourses );
        $this->view->render('instructor/index.html.twig',$params);	
    }
    public function showStudents($instructor_id, $course_id){
  
        
        $students = Instructor::getMyCourseStudents($instructor_id, $course_id);
        $course = Course::getInstance($course_id);
        
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(), 'course'=>$course, 'students'=>$students );
        $this->view->render('instructor/studentlist.html.twig',$params);  
    }    
}