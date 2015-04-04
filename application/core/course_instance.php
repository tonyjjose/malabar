<?php

/**
 * CourseInstance Object
 *
 * A simple object to hold the information of a particular instance of course done by a student.
 */

class CourseInstance
{
	private $course;
	private $instructor;
	private $status;
	private $join_date;

	function __construct (Course $course, Instructor $instructor, $join_date, $status) {
		$this->course = $course;
		$this->instructor = $instructor;
		$this->join_date = strtotime($join_date);
		$this->status = $status;
	}
    public function getCourse(){
        return $this->course;
    }
    public function getInstructor(){
        return $this->instructor;
    }
    public function getJoinDate(){
        return date("j M Y", $this->join_date);
    }
    public function getStatus(){
        return $this->status;
    }
}
