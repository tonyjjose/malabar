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
    * Edit student process.
    *
    * We do not update password here.
    * @return bool success state
    */
    public function editSave()
    {
        //get the inputs
        $id = Request::post('user_id');
        $name = Request::post('user_name');
        $email = Request::post('user_email');
        $age = (int)Request::post('user_age');
        $sex = Request::post('user_sex');
        $qual = Request::post('user_qual');
        $bio = Request::post('user_bio');
        $phone = Request::post('user_phone');
        $mobile = Request::post('user_mobile');
        $address = Request::post('user_address');
        $mode = Request::post('user_course_mode');

        //since it is a checkbox it wont be set if it was not checked by user.
        $anon = (Request::post('user_anon') == 'yes') ? YES : NO;        

        //ok we have the inputs, validate them
        if(!$name || strlen($name) == 0 || strlen($name) > 64) {
            Feedback::addNegative('Failed! user name is invalid.');
            return false;
        }  
        if(strlen($email) > 64 || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            Feedback::addNegative('Failed! user email not valid.');
            return false;
        }              
        //Check if the user email already exist for another user
        if (User::emailExistsForAnotherUser($email,$id)) {
            Feedback::addNegative('Failure! user email already exists.');
            return false;
        }
        if($age < 14 || $age > 99) {
            Feedback::addNegative('Failed! user should be older than 14.');
            return false;
        }  

        //OK try to add to db       
        $success = Student::update($id, $name, $email, $age, $sex, $qual, $bio, $phone, 
        $mobile, $address, $mode, $anon); 

        //has it got updated? if so success.
        if ($success) {
            Feedback::addPositive("Success! user '{$name}' updated.");
            return true;
        }  

        //We come here if its not updated properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;
        
    }

    public function saveAssignment ()
    {
        var_dump($_FILES['assignmentfile']);

        //get the inputs
        $filename = basename($_FILES['assignmentfile']['name']);
        $user = Session::get('user_name');
        $user_id = Session::get('user_id');
        $desc = Request::post('desc');
        $course_id = Request::post('course_id');

        //create the time string to be put in DB.
        $upload_time = date('Y-m-d H:i:s', time()); 

        //disk file name, create unique file name to store to the disk, we use
        //form username_timestring_filename.ext
        $diskfilename =  "{$user}_".strtotime($upload_time)."_{$filename}";    
        
        //the upload directore
        $dir = UPLOAD_DIR;

        echo $diskfilename."\n";
        echo basename($_FILES["assignmentfile"]["name"])."\n";
        echo strtolower(pathinfo($filename,PATHINFO_EXTENSION));

        //validate the inputs
        if(!isset($_FILES['assignmentfile']) || strlen($filename) < 1) {
            Feedback::addNegative('Failed! Please select the assignmment file to upload');
            return false;            
        }

        if(!isset($course_id)) {
            Feedback::addNegative('Failed! Please select the course.');
            return false;               
        }

        // Check file size
        if ($_FILES['assignmentfile']['size'] > 500000) {
            Feedback::addNegative('Failed! File size is too large.');
            return false;
        } 

        //check for file type.
        $allowtype = array('doc', 'docx', 'xml', 'txt', 'pdf' , 'odt');
        if (!in_array(strtolower(pathinfo($filename,PATHINFO_EXTENSION)), $allowtype)) {
            Feedback::addNegative('Failed! File type is not allowed.');
            return false;            
        }

        //check description
        if (strlen($desc) > 255) {
            Feedback::addNegative('Failed! Description is too large.');
            return false;
        }

        //OK try to copy the file to our assignement director.
        if (!move_uploaded_file($_FILES['assignmentfile']['tmp_name'], $dir.$diskfilename)) {
            Feedback::addNegative('Failed! Assignment file not saved to server.');
            return false;
        }        

        //OK, looks fine, lets try to add to db       
        $success = Student::saveAssignment($filename, $desc, $upload_time, $user_id, $course_id); 

        //has it got saved? if so success.
        if ($success) {
            Feedback::addPositive("Success! Assignments uploaded.");
            return true;
        }  

        //We come here if its not saved properly, notify it and exit
        Feedback::addNegative('Failed! Unknown reason.');
        return false;



//         $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// // Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// && $imageFileType != "gif" ) {
//     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $uploadOk = 0;
// }
// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//     echo "Sorry, your file was not uploaded.";
// // if everything is ok, try to upload file
// } else {
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//         echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
//     } else {
//         echo "Sorry, there was an error uploading your file.";
//     }
// }        
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
            Feedback::addNegative('You do not do this course');
            echo "string";
            return null;
        }

        return Student::getCourseMates($id,$course_id);
    }
}