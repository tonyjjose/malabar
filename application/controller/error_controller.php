<?php

/**
 * ErrorController class
 *
 * Handles all the requests for the error pages.
 * 
 */
class ErrorController extends Controller
{
    /**
     * The default error message.
     * Lets display an unknown error here.
     */
    public function index()
    {       
        $this->view->render('error/index.html.twig');
    }

    /**
     * Invalid URL or page
     * 
     */    
    public function invalid()
    {
        header('HTTP/1.0 404 Not Found');
        //should we redirect to a custom page?       
        $this->view->render('error/invalid_page.html.twig');
    }

    /**
     * Not authorised for access
     * 
     */     
    public function noAuth()
    {      
        $this->view->render('error/no_auth.html.twig');
    }

    /**
     * Display the approval message
     * 
     */     
    public function approval()
    {      
        $this->view->render('error/approval.html.twig');
    }    
}
