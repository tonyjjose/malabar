<?php

/**
 * ManagerModel
 *
 * Handles the Manager related bussiness logic
 */

class ManagerModel
{       

    /**
    * Student enrollment save process
    */
    public function enrolSave()
    {
    	//get the inputs
        $student_id = Request::post('student_id');
        $course_id = Request::post('course_id');
        $instructor_id = Request::post('instructor_id');
        
        //all new enrollments are assumed to be active
        $status = COURSE_INSTANCE_ACTIVE; 

        //check 
        if (!Student::isUserStudent($student_id)) {
        	Feedback::addNegative('Failed! User is not a student');
        	return false;
        }
        //check 
        if (!Instructor::isUserInstructor($instructor_id)) {
            Feedback::addNegative('Failed! Instructor chosen is not an Instructor');
            return false;
        }

        if(!$student_id || !$course_id || !$instructor_id) {
            Feedback::addNegative('Failed! Choose a course or instructor');
            return false;            
        }

        //create the time string to be put in DB.
        $join_date = date('Y-m-d H:i:s', time());

        $success = Student::saveCourseInstance($student_id,$course_id,$instructor_id, $join_date, $status);

        if ($success) {
      		Feedback::addPositive('Success! Student enrolled for course.');
      		return true;
    	}  	

    	Feedback::addNegative('Failed! Student not enrolled.');
    	return false;
    }

    /**
    * Student enrollment edit process
    */    
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

    /**
    * Disenrollment process
    */  
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