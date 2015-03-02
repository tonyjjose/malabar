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
}
