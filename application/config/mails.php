<?php

/**
 * Mails used in the application.
 */

return array("MAIL_NEWREG_STUDENT" => "Dear _stuName,<br>
<br>
We have received your registration request.<br>
Please wait for our staff to review your application and approve the registration.<br>
Once you are approved you can login to the site using your email and password.<br>
You will be contacted by the Course Manager very soon.<br>
<br>
Your registration info:<br>
Email: _stuEmail<br>
Password: _stuPass<br>
<br>
Regards<br>
Malabar Bible Courses",
"MAIL_NEWREG_STUDENT_SUBJECT" => "New registration at Malabar Bible Courses",
"MAIL_NEWREG_MANAGER" => "Dear Manager,<br>
We have a new student registration.<br>
<br>
Kindly review the application  at <br>
<a href='_stuLink'>_stuLink</a> <br>
and approve the student and do the further follow up. <br>
<br>
Details of the application<br>
Student Name: _stuName<br>
Student Email: _stuEmail<br>
<br>
Regards<br>
Malabar Bible Courses",
"MAIL_NEWREG_MANAGER_SUBJECT" => "Notification for new user registration",
"MAIL_APPROVED_STUDENT" => "Dear _stuName,<br>
<br>
Your application has been reviewed and approved by the Malabar Bible Courses staff. <br>
You will be contacted soon by the Course Manager who will guide you through the process of course enrollment.<br>
You can also login to the website using your email address and password.<br>
<br>
Regards<br>
Malabar Bible Courses",
"MAIL_APPROVED_STUDENT_SUBJECT" => "You registration at Malabar Bible Courses has been approved",
"MAIL_NEWASSIGNMENT_INSTRUCTOR" => "Dear _instName,<br> 
<br>
A new assignement has been submitted by _stuName.<br> 
You can download the assignment here <br>
<a href='_assignLink'>_assignLink</a> <br> 
Please evaluate it and revert back to the student.<br>
<br>
Details of the assignment<br>
Student: _stuName<br>
FileName: _assignName<br>
Description: _assignDesc<br>
Date: _assignDate<br>
<br>
Regards<br>
Malabar Bible Courses",
"MAIL_NEWASSIGNMENT_INSTRUCTOR_SUBJECT" => "Notification for new assignment submission"
);
