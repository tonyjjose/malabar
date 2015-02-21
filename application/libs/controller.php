<?php

/**
 * This is the "base controller class". All other "real" controllers extend this class.
 */
class Controller
{
    /**
     * @var null Database Connection
     */
    public $db = null;

    /**
     * Check first if we are authorised to enter.
     *
     * Whenever a controller is created, open a database connection too. The idea behind is to have ONE connection
     * that can be used by multiple models.
     *
     * We initialize a view.
     */
    function __construct()
    {
        /*
        * We initialize a session here. Past here is not accessible to non logged in users.
        * They will be redirectd to the login page.
        */
        Session::init();

        if (!isset($_SESSION['user_logged_in'])) {

                //We check the URL to see if it is to login[or login related] page, if so we allow, 
                //otherwise, we redirect to login page.
                if (strpos($_SERVER['REQUEST_URI'], '/app/login') === false){
                     
                    Session::destroy();
                    Redirect::to('login'); 
                    //exit(); //should we enable this so as to prevent curl?? may be we should.                   
                }
            }

        $this->openDatabaseConnection();
        
        //We initialiase a view object for easier loading of our views.
        $this->view = new View();

    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection()
    {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        //$this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
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
     * loadModel("Song") would include models/song_model.php, and create the model object in the controller, also
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
