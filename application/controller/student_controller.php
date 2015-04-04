<?php

/**
 * StudentController class
 * 
 * This handles all the student related requests like, URL/student/...
 *
 */
class StudentController extends Controller
{
    function __construct()
    {  
       parent::__construct();

       if(!(Session::get('user_type') == ROLE_STUDENT)) {
            Redirect::to('error/noauth');
       }
    }

    /**
     * Display the students' dashboad.
     * 
     * We display a short profile information of the student. His courses, and links to various
     * actions that he can perform. We also list his assignments.
     */
    public function index()
    { 
        $id = Session::get('user_id');

        $student = Student::getInstance($id);
        //get his course instances
        $student->loadMyCourses();
        $assignments = Student::getAllAssignments($id);

        $params = array('user'=>$student, 'assignments'=>$assignments);
        $this->view->render('student/index.html.twig',$params);	
    }

    /**
     * Display the list of his course mantes
     *
     * The course ID is provided in URL.
     */
    public function showCourseMates($course_id)
    {
        $student_model = $this->loadModel('student');
        $students = $student_model->getCourseMates($course_id);
        
        $params = array('students'=>$students);
        $this->view->render('student/showcoursemates.html.twig',$params);                
    }
}