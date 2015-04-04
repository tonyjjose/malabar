<?php

/**
 * Assignment Object
 *
 * A simple object to hold the information of a particular instance of assignment uploaded by a student.
 */
class Assignment
{
    private $id;
	private $course;
	private $student;
	private $filename;
	private $desc;
	private $date;
	private $linkfile;

	function __construct ($id, Student $student, Course $course, $filename, $desc, $date)
	 {
        $this->id = $id;
	 	$this->student = $student;
		$this->course = $course;
		$this->filename = $filename;
		$this->date = strtotime($date);
		$this->desc = $desc;
	}
    public function getId() {
        return $this->id;
    }
    public function getCourse(){
        return $this->course;
    }
    public function getStudent(){
        return $this->student;
    }
    public function getDate(){
        return date("j M Y", $this->date);
    }
    public function getFileName(){
        return $this->filename;
    }
    public function getDescription() {
    	return $this->desc;
    }
    public function getLinkFile() {
    	return $this->student->getId()."_".$this->date."_".preg_replace('/[^a-zA-Z0-9._]/','',$this->filename);
    }
}