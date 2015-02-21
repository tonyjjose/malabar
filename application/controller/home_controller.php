<?php

/**
 * Class HomeController
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class HomeController extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://..../home/index
     */
    public function index()
    {
        //as of now display the home, later we will redirect to respective pages based on user roles.
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );        
        $this->view->render('home/index.html.twig', $params);

    }
}
