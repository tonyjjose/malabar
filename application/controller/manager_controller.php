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
     * We display a short profile information, list of latest users, and 
     * links for various actions he can perform.
     */
    public function index()
    {
        $id = Session::get('user_id');

        $manager = Manager::getInstance($id);
        $users = User::getLatestUsers();

        $params = array('user'=>$manager,'users'=>$users);       
        $this->view->render('manager/index.html.twig', $params);
    }

    /**
     * Display the list of students or details of a particular student
     *
     * If an id is given we display that particular student. 
     * The enrolled coursed will also be listed along with the provisions to make new
     * enrollment, edit and disenrol.
     */     
    public function student($id=null)
    {
        if(is_null($id)) {
            $students = Student::getAllStudents();
            $params = array('users'=>$students );
            $this->view->render('manager/viewstudents.html.twig',$params);
        } else {
            $student = Student::getInstance($id);
            $student->loadMyCourses();
            $params = array('user'=>$student);
            $this->view->render('manager/viewstudent.html.twig',$params);
        }        
    }

    /**
     * Display the list of instructors or details of a particular instructor
     *
     * If an id is given we display that particular instructor. 
     * The enrolled coursed will also be listed along with the provisions to make new
     * enrollment, edit and disenrol.
     */     
    public function instructor($id=null)
    {
        if(is_null($id)) {
            $instructors = Instructor::getAllInstructors();
            $params = array('users'=>$instructors);
            $this->view->render('manager/viewinstructors.html.twig',$params);
        } else {
            $instructor = Instructor::getInstance($id);
            //$instructor->loadMyCourses();
            $params = array('user'=>$instructor);
            $this->view->render('manager/viewinstructor.html.twig',$params);
        }        
    }
    /**
     * Display the list of students of the given instrucotr for the 
     * given course
     */
    public function courseStudents($instructor_id, $course_id)
    {
        $students = Instructor::getMyCourseStudents($instructor_id, $course_id);
        
        $params = array('students'=>$students);
        $this->view->render('manager/coursestudentlist.html.twig',$params);

    }
    /**
     * Display the new enrol form
     */    
    public function enrol($id)
    {
        $student = Student::getInstance($id);
        //get the list of unenrolled active courses
        $courses = Student::getUnEnrolledCourses($id);
        //lets get the list of active and approved instructors
        $instructors = Instructor::getAllActiveInstructors();

        $params = array('student'=>$student, 'courses'=>$courses,'instructors'=>$instructors);
        $this->view->render('manager/enrol.html.twig',$params);
    }

    /**
     * POST request handler for enrol form.
     */     
    public function enrolSave()
    {
        $id = Request::post('student_id');        
        $manager_model = $this->loadModel('Manager');
        $success = $manager_model->enrolSave();     
        Redirect::to("manager/student/{$id}");          
    }    

    /**
     * Display the edit enrol form
     */
    public function editEnrol($student_id, $course_id)
    {
        $student = Student::getInstance($student_id);
        $course = Course::getInstance($course_id);
        $instructors = Instructor::getAllActiveInstructors();
        $courseInstance = Student::getCourseInstance($student_id, $course_id);

        $params = array('student'=>$student, 'course'=>$course,'instructors'=>$instructors,'courseInstance'=>$courseInstance);
        $this->view->render('manager/editenrol.html.twig',$params);         
    }  

    /**
     * POST request handler for edit enrol form.
     */   
    public function enrolEditSave()
    {
        $id = Request::post('student_id');
        $manager_model = $this->loadModel('Manager');
        $success = $manager_model->editEnrolSave();   

        Redirect::to("manager/student/{$id}");    
    }

    /**
     * Display the disenrol confimation page
     */   
    public function disEnrol($student_id, $course_id)
    {
        $student = Student::getInstance($student_id);
        $course = Course::getInstance($course_id);
        $params = array('student'=>$student, 'course'=>$course,);
        $this->view->render('manager/disenrol.html.twig',$params);           
    }

    /**
     * POST request handler for dis enrol form.
     */    
    public function disEnrolSave()
    {
        $id = Request::post('student_id');
        $manager_model = $this->loadModel('Manager');
        $manager_model->disEnrolSave(); 
        Redirect::to("manager/student/{$id}");          
    }
}