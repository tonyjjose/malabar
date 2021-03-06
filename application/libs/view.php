<?php

/**
 * Class View
 *
 * Provides the render methods and does the twig initialization
 */
class View
{

    //The twig variables.
    private $loader = null;
    private $twig = null;


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
        $this->twig = new Twig_Environment($this->loader, array('cache' => false,'debug' => true));   #no cache as we are coding. 
        
        //add a twig global variable that holds the project URL.
        $this->twig->addGlobal('URL', URL);        
    }

    /**
     * This will render the template requested. 
     * Note that the parameter list must be an array for twig.
     *
     */
    public function render($template, $array=array())
    {
        echo $this->twig->render($template,$array);
        //Clear feedback. Is it not the right place to do it???
        Feedback::clear();
    }

    /**
     * This is included if we need to bypass twig for whatever reason we may need, if any.
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