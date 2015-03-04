<?php

/**
 * UserModel
 *
 * Handles the users related bussiness logic
 */

class ManagerModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */        

    public function enrolSave()
    {

    	//get the inputs
        $student_id = Request::post('student_id');
        $course_id = Request::post('course_id');
        $instructor_id = Request::post('instructor_id');
        $status = Request::post('status');  

        //check 
        if (!Student::isUserStudent($student_id)) {
    	Feedback::addNegative('Failed! User is not a student');
    	return false;
        }

        $success = Student::saveCourseInstance($student_id,$course_id,$instructor_id,$status);

        if ($success) {
      		Feedback::addPositive('Success! Student enrolled for course.');
      		return true;
    	}  	

    	Feedback::addNegative('Failed! Student not enrolled.');
    	return false;
    }
    public function editEnrolSave()
    {

    	//get the inputs
        $student_id = Request::post('student_id');
        $course_id = Request::post('course_id');
        $instructor_id = Request::post('instructor_id');
        $status = Request::post('status');  

        $success = Student::updateCourseInstance($student_id,$course_id,$instructor_id,$status);

        if ($success) {
      		Feedback::addPositive('Success! Enrollment updated.');
      		return true;
    	}  	

    	Feedback::addNegative('Failed! Enrollment not updated.');
    	return false;
    }

    public function disEnrolSave()
    {
    	//get the inputs
        $student_id = Request::post('student_id');
        $course_id = Request::post('course_id');

        $success = Student::deleteCourseInstance($student_id,$course_id);
        if ($success) {
      		Feedback::addPositive('Success! Student disenrolled.');
      		return true;
    	}  	

    	Feedback::addNegative('Failed! Enrollment not removed.');
    	return false;        
    }
}



