<?php

/**
 * Class ReportController
 * 
 * This handles all the report related requests like, URL/report/...
 */

class ReportController extends Controller
{
    function __construct()
    {  
       parent::__construct();

       if(!(Session::get('user_type') == ROLE_MANAGER)) {
            Redirect::to('error/noauth');
       }
    } 

    /**
     * Display Student reports. 
     * Accept parameters and display reports.
     */
    public function student($action = null)
    {
    	//$instructors = Instructor::getAllInstructors();
        if(!$action) {
            $courses = Course::getAllCourses();
            $params = array('courses'=>$courses);        
            $this->view->render('report/student.html.twig', $params);
        } elseif ($action == 'view') {
            $report_model = $this->loadModel('Report');
            $students = $report_model->studentList();     
            $params = array('students'=>$students); 
            //var_dump($students);       
            $this->view->render('report/viewstudentreport.html.twig', $params);
        }   
    }

    /**
     * Display Instructor reports. 
     * Accept parameters and display reports.
     */    
    public function instructor($action = null)
    {
        //$instructors = Instructor::getAllInstructors();
        if(!$action) {
            $courses = Course::getAllCourses();
            $params = array('courses'=>$courses);        
            $this->view->render('report/instructor.html.twig', $params);
        } elseif ($action == 'view') {
            $report_model = $this->loadModel('Report');
            $instructors = $report_model->instructorList();     
            $params = array('instructors'=>$instructors); 
            //var_dump($students);       
            $this->view->render('report/viewinstructorreport.html.twig', $params);
        }   
    }

    /**
     * Display Course reports.
     */    
    public function course()
    {
        $courses = Course::getAllCourses();
        $params = array('courses'=>$courses);        
        $this->view->render('report/viewcoursereport.html.twig', $params);
    }
}