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
require 'application/core/course.php';

// start the application
$app = new Application();
