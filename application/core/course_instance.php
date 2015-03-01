<?php


/**
 * CourseInstance User
 *
 * A simple object to hold the information of a particular instance of course done by a student.
 */


class CourseInstance
{
	private $course;
	private $instructor;
	private $status;

	function __construct (Course $course, Instructor $instructor, $status) {
		$this->course = $course;
		$this->instructor = $instructor;
		$this->status = $status;

	}
    public function getCourse(){
        return $this->course;
    }
    public function getInstructor(){
        return $this->instructor;
    }
    public function getStatus(){
        return $this->status;
    }
}
