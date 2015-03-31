<?php

/**
 * HomeController class
 *
 * Handle the app/home requests. We just redirct the user based on his type. If no type we send them to login
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
    public function mail()
    {
        $mailer = new Mailer();

        $params = array (
            "_to" => "tonyjose2@gmail.com",
            "_name" => "Tony Jose",
            "_subject" => Mailer::mail('MAIL_NEWREG_STUDENT_SUBJECT'),
            "_msg" => Mailer::mail('MAIL_NEWREG_STUDENT'),
            "_student" => "Chackochan Pathrose",
            "_stuEmail" => "jacky@jj.com",
            "_link" => "http://www.malabarbiblecourses.org/app/user/21",
            "_assignName" => "bcc 29 cahpter. pdf",
            "_assignDate" => "Mar 26 2115",
            "_assignDesc" => "whti is ssuposed to be the assigngiment desc pandarm");

        $mailer->newMail($params);

        $mailer->sendMail();
    }
}
