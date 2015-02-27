<?php

/**
 * Configuration
 *
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

//Set the default timezone for the script.
date_default_timezone_set("Asia/Calcutta");

/**
 * Configuration for: Project URL
 * Put your URL here, for local development "127.0.0.1" or "localhost" (plus sub-folder) is fine
 */
define('URL', 'http://127.0.0.1/app/');

/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'bcc_app');
define('DB_USER', 'root');
define('DB_PASS', 'google');

/**
 * Configuration for: Folders
 * Here you define where your folders are. Unless you have renamed them, there's no need to change this.
 */
define('LIBS_PATH', 'application/libs/');
define('CORE_PATH', 'application/core/');
define('CONTROLLER_PATH', 'application/controllers/');
define('MODELS_PATH', 'application/models/');
define('VIEWS_PATH', 'application/views/');
define('TEMPLATES_PATH', 'application/views/');

/* Path for twig and twig cache.
 * we are giving here filesystem level path. Note sure if this is the correct way.
 * An expert advice or review needed here  -------------???
 * Cache folder is put above the http public folder as it is writable and we dont need it to
 * be accessed from outside world.
 */
define('TWIG_PATH', 'Twig/');
define('TWIG_CACHE_PATH', '../../cache/');


/**
 * Configuration for: Error messages and notices
 *
 * In this project, the error messages, notices etc are all-together called "feedback".
 */
define('FEEDBACK_USERNAME_FIELD_EMPTY', 'Username field was empty.');
define('FEEDBACK_PASSWORD_FIELD_EMPTY', 'Password field was empty.');


/**
 * Program level constants
 *
 *
 **/

CONST YES = 1;
CONST NO = 0;

define('ROLE_NONE', 'N');
define('ROLE_STUDENT', 'S');
define('ROLE_INSTRUCTOR', 'I');
define('ROLE_MANAGER', 'M');

define('SEX_MALE', 'M');
define('SEX_FEMALE', 'F');

define ('COURSE_MODE_EMAIL','E');
define ('COURSE_MODE_POSTAL','P');




