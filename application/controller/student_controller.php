<?php

/**
 * Class StudentController
 * This is a Student controller which handles the Student..
 * We use the URLs ..app/Student/index, app/Student/signin, app/Student/signout etc
 *
 *
 */
class StudentController extends Controller
{
    function __construct()
    {  
       parent::__construct();  	
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to ..app/Student/index. 
     * We use this to show the Student form.
     */
    public function index(){
  
        //Show the Student page
        //we need to pass feedback as we return to same page if we have wrong password. 
        $student = Student::getInstance(5);
        $student->loadMyCourses();
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),'student'=>$student );
        $this->view->render('student/index.html.twig',$params);	
    }

    public function showProfile($id){
        //are we authorised for view?
        if ($id == Session::get('user_id') || Session::get('user_type') == 'M')
        {
            $student = User::getInstance($id);
            $student->loadMyCourses();
            $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
                'student'=>$student );            
            $this->view->render('student/showprofile.html.twig',$params);
        }
        else
        {
            Redirect::to('error/noauth');
        }

    }
    public function showCourseMates($course_id)
    {
        $id = Session::get('user_id');
        $students = Student::getCourseMates($id,$course_id);
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
                'students'=>$students );
        $this->view->render('student/showcoursemates.html.twig',$params);                
    }
    
    public function editProfile($id){
        //are we authorised for view?
        if ($id == Session::get('user_id') || Session::get('user_type') == 'M')
        {
            $student = User::getInstance($id);
            var_dump($student);
            $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive(),
                'student'=>$student );            
            $this->view->render('student/editprofile.html.twig',$params);
        }
        else
        {
            Redirect::to('error/noauth');
        }

    }    
    public function editProfileSave()
    {
        $student_model = $this->loadModel('student');
        $success = $student_model->editSave(); //we dont use $success now.  
        //Redirect::to('user');
        Feedback::printAll();
    }      
}
