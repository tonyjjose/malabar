<?php

/**
 * ManagerController class
 * 
 * This handles all the manager related requests like, URL/manager/...
 *
 */
class ManagerController extends Controller
{
    function __construct()
    {  
       parent::__construct();

       if(!(Session::get('user_type') == ROLE_MANAGER)) {
            Redirect::to('error/noauth');
       }
    }    

    /**
     * Display the managers' dashboad.
     * 
     * We display a short profile information, and links for various actions he can perform.
     */
    public function index()
    {
        $id = Session::get('user_id');

        $manager = Manager::getInstance($id);

        $params = array('user'=>$manager );       
        $this->view->render('manager/index.html.twig', $params);
    }

    /**
     * Display the list of students
     */    
    public function viewStudents()
    {
        $students = Student::getAllStudents();
        $params = array('users'=>$students );
        $this->view->render('manager/viewstudents.html.twig',$params);         
    }

    /**
     * Display the list of students
     */     
    public function viewStudent($id)
    {
        $student = Student::getInstance($id);
        $student->loadMyCourses();
        $params = array('user'=>$student);
        $this->view->render('manager/viewstudent.html.twig',$params);         
    }



    public function editEnrol($student_id, $course_id)
    {
        $student = Student::getInstance($student_id);
        $course = Course::getInstance($course_id);
        $instructors = Instructor::getAllInstructors();
        //var_dump($instructors);
        $courseInstance = Student::getCourseInstance($student_id, $course_id);

        $params = array('student'=>$student, 'course'=>$course,'instructors'=>$instructors,'courseInstance'=>$courseInstance);
        $this->view->render('manager/editenrol.html.twig',$params);         
    }  

    public function enrolEditSave()
    {
        $manager_model = $this->loadModel('manager');
        $success = $manager_model->editEnrolSave();     
        Redirect::to('manager/viewstudent/5');    
    } 
    public function disEnrol($student_id, $course_id)
    {
        $student = Student::getInstance($student_id);
        $course = Course::getInstance($course_id);
        $params = array(
            'student'=>$student, 'course'=>$course,);
        $this->view->render('manager/disenrol.html.twig',$params);           
    } 
    public function disEnrolSave()
    {
        $manager_model = $this->loadModel('manager');
        $success = $manager_model->disEnrolSave();     
        Redirect::to('manager/viewstudent/5');          

    }
    public function enrol($id)
    {
        $student = User::getInstance($id);
        $courses = Student::getUnEnrolledCourses($id);
        var_dump($courses);
        $instructors = Instructor::getAllInstructors(); 
        $params = array(
            'student'=>$student, 'courses'=>$courses,'instructors'=>$instructors);
        $this->view->render('manager/enrol.html.twig',$params);
    }
    public function enrolSave()
    {
        $manager_model = $this->loadModel('manager');
        $success = $manager_model->enrolSave();     
        Redirect::to('manager/viewstudent/5');        
    }    

}