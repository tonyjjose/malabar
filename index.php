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
 */

// load the (optional) Composer auto-loader
// Note: we dont use this so we comment it out.
/*if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
}*/

// load application config (error reporting etc.)
require 'application/config/config.php';

// load application class
require 'application/libs/application.php';
require 'application/libs/controller.php';

//load session 
require 'application/libs/session.php';

// start the application
$app = new Application();
