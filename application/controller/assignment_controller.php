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
        //Feedback::printAll();
        if ($success) {
            Redirect::home(); 
        } else {
            Redirect::to("assignment/upload");
        }
    }
    public function download()
    {
    	$file = Request::get('f');
        $name = Request::get('n');
        echo $name;
    	echo UPLOAD_DIR.$file;
        if (file_exists(UPLOAD_DIR.$file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(UPLOAD_DIR.$file));
            readfile(UPLOAD_DIR.$file);
            echo "we reach here";
            //exit;
        }
echo "we reach here";
    }



}