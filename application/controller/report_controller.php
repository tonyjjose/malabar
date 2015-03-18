<?php

/**
 * Class ManagerController
 * 
 * Handles all manager related stuff
 *
 */
class ReportController extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://..../home/index
     */
    public function student($action = null)
    {
    	//$instructors = Instructor::getAllInstructors();
        if(!$action) {
    	$courses = Course::getAllCourses();
        $params = array('courses'=>$courses);        
        $this->view->render('report/student.html.twig', $params);
        } elseif ($action == 'view') {
        $report_model = $this->loadModel('report');
        $students = $report_model->studentList();     
        $params = array('students'=>$students); 
        //var_dump($students);       
        $this->view->render('report/viewstudentreport.html.twig', $params);
        }   
    }
    public function instructor($action = null)
    {
        //$instructors = Instructor::getAllInstructors();
        if(!$action) {
        $courses = Course::getAllCourses();
        $params = array('courses'=>$courses);        
        $this->view->render('report/instructor.html.twig', $params);
        } elseif ($action == 'view') {
        $report_model = $this->loadModel('report');
        $students = $report_model->instructorList();     
        $params = array('instructors'=>$students); 
        //var_dump($students);       
        $this->view->render('report/viewinstructorreport.html.twig', $params);
        }   
    }
    public function viewStudentReport()
    {
        $report_model = $this->loadModel('report');
        $students = $report_model->studentList();     
        $params = array('students'=>$students); 
        //var_dump($students);       
        $this->view->render('report/viewstudentreport.html.twig', $params);
    }

}