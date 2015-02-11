<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

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
define('DB_NAME', 'login');
define('DB_USER', 'root');
define('DB_PASS', 'google');

/**
 * Configuration for: Folders
 * Here you define where your folders are. Unless you have renamed them, there's no need to change this.
 */
define('LIBS_PATH', 'application/libs/');
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
define('TWIG_PATH', '../Twig/');
define('TWIG_CACHE_PATH', '../../cache/');


/**
 * Configuration for: Error messages and notices
 *
 * In this project, the error messages, notices etc are all-together called "feedback".
 */
define('FEEDBACK_USERNAME_FIELD_EMPTY', 'Username field was empty.');
define('FEEDBACK_PASSWORD_FIELD_EMPTY', 'Password field was empty.');
