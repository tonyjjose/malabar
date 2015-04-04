<?php

/**
 * Welcome to malabarbiblecouses.org private module
 * All the internal pages begin from here. 
 *
 * @author Tony J Jose
 * www.33dots.com/
 * https://github.com/tonyjjose
 *
 *
 * Date 4 Feb 2015	
 *
 * Requirements
 * PHP 5.3+
 * mod_rewrite()
 */

// load application config
require 'application/config/config.php';

// load application classes
require 'application/libs/application.php';
require 'application/libs/controller.php';

//load the password compatibility library
if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("application/libs/password_compatibility_library.php");
}

//PHPMailerAutoloader
require 'PHPMailer/PHPMailerAutoload.php';

/*
* Load helper classes
* 
* @todo Create an auto loader  
*/
require 'application/libs/session.php';
require 'application/libs/view.php';
require 'application/libs/feedback.php';
require 'application/libs/redirect.php';
require 'application/libs/request.php';
require 'application/libs/database.php';
require 'application/libs/mailer.php';
// mails used in system
require 'application/config/mails.php';

/*
* Load bussiness classes
*/
require 'application/core/category.php';
require 'application/core/course.php';
require 'application/core/course_instance.php';
require 'application/core/user.php';
require 'application/core/student.php';
require 'application/core/instructor.php';
require 'application/core/manager.php';
require 'application/core/assignment.php';

// start the application
$app = new Application();
