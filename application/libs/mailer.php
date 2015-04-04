<?php

/**
 * Mailer class.
 *
 * Prepare and send mails for our application.
 * As of now we use PHPMailer to send mails using SMTP. Should we need to change to native sendmail? 
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
		$this->mail->SMTPDebug = PHPMAILER_DEBUB_STATUS;

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

    /**
     * Prepare a new email.
     * We also parse the mail body and substitute the keywords with the values, if provided.
     * @param params associative array. 
     */
	public function newMail($params = array())
	{

		//unset any prev address.
		$this->mail->ClearAddresses();

		//Set who the message is to be sent to
		//$this->mail->addAddress($params['_to'], $params['_name']);

		$this->mail->addAddress("tonyjose2@gmail.com", $params['_name']); /*-------for testing---------*/

		//Set the subject line
		$this->mail->Subject = $params['_subject'];

		//parse the message and replace the keywords
		$msg = $this->prepareMessage($params);

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->mail->msgHTML($msg);
	}

    /**
     * Prepare the message body.
     * We just replaces the keywords in the message body which has the same value as the array key, 
     * with the corresponding array values.
     * Eg: Like, replacing a '_stuName' keyword in message using params['_stuName'] value.
     * @param params associative array.
     * @return string ,prepared message. 
     */
	private function prepareMessage($params)
	{
		return str_replace(array_keys($params), array_values($params), $params['_msg']);
	}

    /**
     * Send the mail
     * @return bool status
     */
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

    /**
     * Get the message body.
     * 
     * The mail texts are stored as an associative array in the mails.php file. Return the 
     * corresponding message of the requested key.
     * @param string messagekey
     * @return bool status
     */
    public static function mail($key)
    {
       $mails = require('application/config/mails.php');
       
       return $mails[$key];
    }
}