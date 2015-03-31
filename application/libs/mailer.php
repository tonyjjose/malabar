<?php

/**
 * Mailer class.
 *
 * Prepare and send mails.
 * As of now we use PHPMailer to send mails.
 *
 */
class Mailer
{
	private $mail;

    /**
     * Initialize the PHPMailer here
     */
    function __construct ()
    {
		//Create a new PHPMailer instance
		$this->mail = new PHPMailer;

		//Tell PHPMailer to use SMTP
		$this->mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->mail->SMTPDebug = 0;

		//Ask for HTML-friendly debug output
		$this->mail->Debugoutput = 'html';

		//Set the hostname of the mail server
		$this->mail->Host = 'smtp.gmail.com';

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$this->mail->Port = 587;

		//Set the encryption system to use - ssl (deprecated) or tls
		$this->mail->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		$this->mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$this->mail->Username = MAIL_FROM;

		//Password to use for SMTP authentication
		$this->mail->Password = MAIL_PASS;

		//Set who the message is to be sent from
		$this->mail->setFrom(MAIL_FROM, MAIL_NAME);

		//Set an alternative reply-to address
		//$mail->addReplyTo('replyto@example.com', 'Malabar Bible Courses');
    }


	public function newMail($params = array())
	{

		//Set who the message is to be sent to
		//$this->mail->addAddress($params['_to'], $params['_name']);

		$this->mail->addAddress("tonyjose2@gmail.com", $params['_name']);

		//Set the subject line
		$this->mail->Subject = $params['_subject'];

		$msg = $this->prepareMessage($params);

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->mail->msgHTML($msg);
	}

	private function prepareMessage($params)
	{
		return str_replace(array_keys($params), array_values($params), $params['_msg']);
	}

	public function sendMail()
	{
		//return true;
		//send the message, check for errors
		if (!$this->mail->send()) {
		    echo "Mailer Error: " . $this->mail->ErrorInfo;
		    return false;
		} else {
		    return true;
		}
	}

    public static function mail($key)
    {
       $mails = require('application/config/mails.php');
       
       return $mails[$key];
    }	

}