<?php

/**
 * StudentModel class
 *
 * Handles the students related bussiness logic.
 */

class StudentModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */        
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
    * Save the uploaded assignment
    *
    * The assignments are saved to ../../assignments folder with a unique filename of the form
    * ID_TimeString_FilenameWithoutSpecialChars.ext 
    * @return bool success state
    */
    public function saveAssignment ()
    {
        //get the inputs
        $filename = basename($_FILES['assignmentfile']['name']);
        $user = Session::get('user_name');
        $user_id = Session::get('user_id');
        $desc = Request::post('desc');
        $course_id = Request::post('course_id');

        //OK, validate the inputs
        if(!isset($_FILES['assignmentfile']) || strlen($filename) < 1) {
            Feedback::addNegative('Failed! Please select the assignmment file to upload');
            return false;            
        }

        if(!isset($course_id)) {
            Feedback::addNegative('Failed! Please select the course.');
            return false;               
        }
        Feedback::addNegative ($_FILES['assignmentfile']['size']);

        // Check file size
        if ($_FILES['assignmentfile']['size'] > 2000000) {
            Feedback::addNegative('Failed! File size is too large.');
            return false;
        } 

        //check for file type.
        $allowtype = array('doc', 'docx', 'xml', 'txt', 'pdf', 'rtf', 'odt', 'zip', '7z', 'gz');
        if (!in_array(strtolower(pathinfo($filename,PATHINFO_EXTENSION)), $allowtype)) {
            Feedback::addNegative('Failed! File type is not allowed.');
            return false;            
        }

        //check description
        if (strlen($desc) > 255) {
            Feedback::addNegative('Failed! Description is too large.');
            return false;
        }

        //Ok, input looks fine. 

        //create the time string to be put in DB.
        $upload_time = date('Y-m-d H:i:s', time()); 

        //disk file name, create unique file name to store to the disk, we use
        //form ID_TimeString_FilenameWithoutSpecialChars.ext
        $diskfilename =  "{$user_id}_".strtotime($upload_time)."_".preg_replace('/[^a-zA-Z0-9._]/','',$filename);    
        
        //the upload directore
        $dir = UPLOAD_DIR;

        //OK try to copy the file to our assignment director.
        if (!move_uploaded_file($_FILES['assignmentfile']['tmp_name'], $dir.$diskfilename)) {
            Feedback::addNegative('Failed! Assignment file not saved to server.');
            return false;
        }        

        //OK, looks fine, lets try to add to db       
        $success = Student::saveAssignment($filename, $desc, $upload_time, $user_id, $course_id); 

        //has it got saved?
        if ($success) {
            Feedback::addPositive("Success! Assignments uploaded.");

            //lets mail the instructor about it.
            //btw, find out the instructor first.
            $instructor = Instructor::getInstructorForAssignment($user_id,$course_id);

            $mailer = new Mailer();
            $params = array (
                "_to" => $instructor->getEmail(),
                "_name" => $instructor->getName(),
                "_subject" => Mailer::mail('MAIL_NEWASSIGNMENT_INSTRUCTOR_SUBJECT'),
                "_msg" => Mailer::mail('MAIL_NEWASSIGNMENT_INSTRUCTOR'),
                "_stuName" => $user,
                "_instName" => $instructor->getName(),
                "_assignName" => $filename,
                "_assignDesc" => $desc,
                "_assignDate" => $upload_time,
                "_assignLink" => URL."assignment/download?f={$diskfilename}&n={$filename}");
            $mailer->newMail($params);
            $mailer->sendMail();   

            return true;
        }  

        //We come here if its not saved properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;       
    }

    /**
    * Get the list of sudents taking the same course.
    *
    * The list is returned only if the student takes part in that same course.
    * @return bool success state
    */
    public function getCourseMates ($course_id)
    {
        $id = Session::get('user_id');

        if (!(Student::isStudentDoingCourse($id,$course_id))) {
            Feedback::addNegative('You are not actively doing the course.');
            return null;
        }

        return Student::getCourseMates($id,$course_id);
    }
}