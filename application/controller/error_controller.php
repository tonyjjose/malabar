<?php

/**
 * Class ErrorController
 *
 *
 */
class ErrorController extends Controller
{
    /**
     * the one and only error page as of now.
     * 
     */
    public function index()
    {
        //header('HTTP/1.0 404 Not Found');
        //as of now display a simple error, later we will redirect to respective error pages.
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );        
        $this->view->render('error/index.html.twig', $params);

    }
}
