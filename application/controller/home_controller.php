<?php

/**
 * HomeController class
 *
 * Handle the app/home requests. We just redirct the user based on his type.
 * If no type we send them to login
 */
class HomeController extends Controller
{
    /**
     * Home request to redirect to home.  
     */  
    public function index()
    {
        Redirect::home();
    }
}
