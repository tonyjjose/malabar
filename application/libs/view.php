<?php

/**
 * The View class.
 *
 * Provides the view render methods and does the twig initialization
 */
class View
{
    //The twig variables.
    private $loader = null;
    private $twig = null;

    /**
     * Initialize the twig here
     */
    function __construct ()
    {
        //load the twig library. We installed it in the application directory.
        require_once TWIG_PATH . 'Autoloader.php';
        Twig_Autoloader::register();

        $this->loader = new Twig_Loader_Filesystem(VIEWS_PATH);

        /* set the cache directory. here we set it one level above the app directory so that
         * it is not accessible from outside world. 
         * Note that the dir should be writable. 
         */
        //$this->twig = new Twig_Environment($this->loader, array('cache' => TWIG_CACHE_PATH,));
        $this->twig = new Twig_Environment($this->loader, array('cache' => false,'debug' => true));   #no cache as we are in dev mode. 
        
        //add a twig global variable that holds the project URL.
        $this->twig->addGlobal('URL', URL); 
        $this->twig->addGlobal('SESSION_USER', Session::get('user_name'));
        $this->twig->addGlobal('SESSION_LAST', Session::get('user_last_login'));    
    }

    /**
     * The default twig template renderer
     *  
     * The template specified will be rendered using the given parameters. We also set
     * the feedback parameters before and after displaying the view, we clear the feedback entries from 
     * $_Session[]
     * 
     * @param string $template Template file name
     * @param string[] $params The parameter list
     */
    public function render($template, $params=array())
    {
        //we add the feedback here to the $params.This may not be the most elegant postition
        //theoretically, but it saves a lot of typing.
        $params['feedback_negative'] = Feedback::getNegative();
        $params['feedback_positive'] = Feedback::getPositive();

        echo $this->twig->render($template,$params);
        
        //Clear feedback. Is it not the right place to do it???
        Feedback::clear();
    }

    /**
     * Twig less render function.
     * Provided incase we need to display a non template file
     */
    public function renderWithoutTwig($filename)
    {
        //display the file..
        require VIEWS_PATH . $filename . '.php';
    }

    /**
     * Checks if the passed string is the currently active controller.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller
     * @return bool Shows if the controller is used or not
     */
    private function checkForActiveController($filename, $navigation_controller)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];

        if ($active_controller == $navigation_controller) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Checks if the passed string is the currently active controller-action (=method).
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_action
     * @return bool Shows if the action/method is used or not
     */
    private function checkForActiveAction($filename, $navigation_action)
    {
        $split_filename = explode("/", $filename);
        $active_action = $split_filename[1];

        if ($active_action == $navigation_action) {
            return true;
        }
        // default return of not true
        return false;
    }

    /**
     * Checks if the passed string is the currently active controller and controller-action.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller_and_action
     * @return bool
     */
    private function checkForActiveControllerAndAction($filename, $navigation_controller_and_action)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        $active_action = $split_filename[1];

        $split_filename = explode("/", $navigation_controller_and_action);
        $navigation_controller = $split_filename[0];
        $navigation_action = $split_filename[1];

        if ($active_controller == $navigation_controller AND $active_action == $navigation_action) {
            return true;
        }
        // default return of not true
        return false;
    }
}