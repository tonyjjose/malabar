<?php

/**
 * Class ManagerController
 * 
 * Handles all manager related stuff
 *
 */
class ManagerController extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://..../home/index
     */
    public function index()
    {
        //as of now display the home, later we will redirect to respective pages based on user roles.
        $params = array('feedback_negative'=>Feedback::getNegative(), 'feedback_positive'=>Feedback::getPositive() );        
        $this->view->render('manager/index.html.twig', $params);

    }
    
    public function addCourse()
    {


    }
}