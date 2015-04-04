<?php

/**
 * AssignmentController class
 * 
 * This handles the assignment upload/download
 */
class AssignmentController extends Controller
{
    function __construct()
    {  
       parent::__construct();
    }

    /**
     * Display the upload form
     */ 
    public function upload()
    {
        $id = Session::get('user_id');

        if (!Student::isUserStudent($id)) {        
            Redirect::to('error/noauth');  
        }

    	$courses = Student::getEnrolledCourses($id);

        $params = array('courses'=>$courses);
        $this->view->render('student/uploadassignment.html.twig',$params);
    }

    /**
     * POST request handler for upload save. 
     * Call the student model and save if everything is fine.
     */
    public function uploadSave()
    {
        $student_model = $this->loadModel('Student');
        $success = $student_model->saveAssignment(); 
        
        if ($success) {
            Redirect::home(); 
        } else {
            Redirect::to("assignment/upload");
        }
    }

    /**
     * Stream the file to the user for download.
     */ 
    public function download()
    {
    	//get file details from GET
        $file = Request::get('f');
        $name = Request::get('n');

        if (file_exists(UPLOAD_DIR.$file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(UPLOAD_DIR.$file));
            readfile(UPLOAD_DIR.$file);
            //exit;
        }
    }
}