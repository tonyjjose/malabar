<?php

/**
 * The base controller class
 *
 * Provided the base methods required for all the controllers and also does the authentication check and session management.
 *  Unauthenticated users are not allowed past here, they are redirected to login page.  
 *
 */
class Controller
{
    /**
     * @var null Database Connection
     */
    public $db = null;

    /**
     * The base constuctor for all controllers 
     *
     * Checks first if we are authorised to enter.
     * Whenever a controller is created, open a database connection too. The idea behind is to have ONE connection
     * that can be used by multiple models. We also initialize the view object.
     */
    function __construct()
    {
        /*
        * We initialize a session here. Past here is not accessible to non logged in users.
        * They will be redirectd to the login page.
        */
        Session::init();

        if (!isset($_SESSION['user_logged_in'])) {

                //We check the URL to see if it is to login or error controller, if so we allow, 
                //otherwise, we redirect to login page.
                if (!(get_class($this) === 'ErrorController' || get_class($this) === 'LoginController')) {
                    Session::destroy();
                    Redirect::to('login'); 
                    //exit(); //should we enable this so as to prevent curl?? may be we should.                     
                }
            }

        //get the connction
        $this->openDatabaseConnection();
        
        //We initialiase the view
        $this->view = new View();

    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection()
    {
        // set the (optional) options of the PDO connection. we use FETCH_OBJ and may be later we change the ERRMODE
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // generate a database connection, using the PDO connector
        try {
            $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS, $options);           
        } catch (PDOException $e) {
            echo 'Database connection failed: ' . $e->getMessage();
            // End the application.
            //die('Database connection could not be established.');                       
        }
    }

    /**
     * Load the model with the given name.
     * 
     * loadModel("Student") would include models/student_model.php, and create the model object in the controller, also
     * passing the db object.
     * Note that the model class name is written in "CamelCase", the model's filename is in lowercase letters
     * @param string $name The name of the model
     * @return object model
     */
    public function loadModel($name)
    {
        // model file names are like "login_model.php"
        require 'application/models/' . strtolower($name) . '_model.php';
        
        // all model classes have names like "LoginModel"
        $modelName = $name . 'Model';

        // return new model (and pass the database connection to the model)        
        return new $modelName($this->db);
    }
}
