<?php

/**
 * Class ManagerController
 * 
 * Handles all manager related stuff
 *
 */
class ManagerController extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://..../home/index
     */
    public function index()
    {
        //as of now display the home, later we will redirect to respective pages based on user roles.
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );        
        $this->view->render('manager/index.html.twig', $params);

    }
    public function viewStudents()
    {
        $students = Student::getAllStudents();
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
            'students'=>$students );
        $this->view->render('manager/viewstudents.html.twig',$params);         
    }    
    public function viewStudent($id)
    {
        $student = Student::getInstance($id);
        $student->loadMyCourses();
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),'user'=>$student );
        $this->view->render('manager/viewstudent.html.twig',$params);         
    }

    public function editEnrol($student_id, $course_id)
    {
        $student = Student::getInstance($student_id);
        $course = Course::getInstance($course_id);
        $instructors = Instructor::getAllInstructors();
        //var_dump($instructors);
        $courseInstance = Student::getCourseInstance($student_id, $course_id);

        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
            'student'=>$student, 'course'=>$course,'instructors'=>$instructors,'courseInstance'=>$courseInstance);
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
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
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
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
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