<?php

/**
 * Configuration for the Project
 *
 * We use this page to set error reporting, timezone, and various other constants used in the application.
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 * Change this later
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

//Set the default timezone for the script.
date_default_timezone_set("Asia/Calcutta");

/**
 * Configuration for: Project URL
 */
define('URL', 'http://127.0.0.1/app/');
define('WWW', 'http://www.malabarbiblecourses.org/');

/**
 * Configuration for: Database
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'bcc_app');
define('DB_USER', 'root');
define('DB_PASS', 'google');

/**
 * Configuration for: Mail
 */
define('MAIL_FROM', 'malabarbiblecourses@gmail.com');
define('MAIL_NAME', 'Malabar Bible Courses');
define('MAIL_PASS', 'malaGMbcc3@');

/**
 * Configuration for: Folders
 */
define('LIBS_PATH', 'application/libs/');
define('CORE_PATH', 'application/core/');
define('CONTROLLER_PATH', 'application/controllers/');
define('MODELS_PATH', 'application/models/');
define('VIEWS_PATH', 'application/views/');
define('TEMPLATES_PATH', 'application/views/');

/* Path for twig and twig cache.
 *
 * we are giving here filesystem level path. Note sure if this is the correct way.
 * An expert advice or review needed here  -------------???
 * Cache folder is put above the http public folder as it is writable and we dont need it to
 * be accessed from outside world.
 */
define('TWIG_PATH', 'Twig/');
define('TWIG_CACHE_PATH', '../../cache/');

/**
 * Assignment uploads directory.
 * Note that his should be writable.
 **/
define('UPLOAD_DIR', '../../assignments/');


/**
 * Configuration for: Success and error feedback
 * May not be used but just provided for future if needed.
 */
define('FEEDBACK_USERNAME_FIELD_EMPTY', 'Username field was empty.');
define('FEEDBACK_PASSWORD_FIELD_EMPTY', 'Password field was empty.');

/**
 * Application specific constants
 *
 **/

//why did we use CONST just here and define() else where? 
CONST YES = 1;
CONST NO = 0;

CONST ACTIVE = 1;
CONST INACTIVE = 0;

define('ROLE_NONE', 'N'); //not used as of now
define('ROLE_STUDENT', 'S');
define('ROLE_INSTRUCTOR', 'I');
define('ROLE_MANAGER', 'M');

define('SEX_MALE', 'M');
define('SEX_FEMALE', 'F');

define ('COURSE_MODE_EMAIL','E');
define ('COURSE_MODE_POSTAL','P');

define ('COURSE_INSTANCE_ACTIVE','A');
define ('COURSE_INSTANCE_INACTIVE','I');
define ('COURSE_INSTANCE_COMPLETED','C');
