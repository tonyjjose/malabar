<?php

/**
 * Welcome to malabarbiblecouses.org private module
 * All the internal pages begin from here. 
 *
 * By Tony J Jose
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

// load application config (error reporting etc.)
require 'application/config/config.php';

// load application class
require 'application/libs/application.php';
require 'application/libs/controller.php';

//load the password compatibility library

if (version_compare(PHP_VERSION, '5.5.0', '<')) {
//load the libraray.
    require_once("application/libs/password_compatibility_library.php");
}

/*
* To Do: Create a auto loader class 
*
* load helper classes
*/
require 'application/libs/session.php';
require 'application/libs/view.php';
require 'application/libs/feedback.php';
require 'application/libs/redirect.php';
require 'application/libs/request.php';
require 'application/libs/database.php';

/*
* load bussiness classes
*/
require 'application/core/category.php';
require 'application/core/course.php';
require 'application/core/user.php';
require 'application/core/student.php';
require 'application/core/instructor.php';
require 'application/core/manager.php';

// start the application
$app = new Application();
