<?php

/**
 * StudentController class
 * 
 * This handles all the student related requests like, URL/student/...
 *
 */
class AssignmentController extends Controller
{
    function __construct()
    {  
       parent::__construct();

       if(!(Session::get('user_type') == ROLE_STUDENT)) {
            //Redirect::to('error/noauth');
       }
    }

    public function upload()
    {
    	$id = Session::get('user_id');

    	$courses = Student::getEnrolledCourses($id);

        $params = array('courses'=>$courses);
        $this->view->render('student/uploadassignment.html.twig',$params);
    }

    public function uploadSave()
    {
        $id = Session::get('user_id');

        $student_model = $this->loadModel('Student');
        $success = $student_model->saveAssignment(); 
        Feedback::printAll();
        if ($success) {
            Redirect::home(); 
        } else {
            Redirect::to("assignment/upload");
        }
    }
    public function download()
    {
    	$f = Request::get('f');
    	echo $f;

    }



}