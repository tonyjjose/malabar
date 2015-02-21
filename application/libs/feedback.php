<?php 

/**
 * Class Feedback
 *
 * Simple abstraction for feedbacks. we just store them in Session variables
 */
class Feedback
{

	public static function addPositive($feedback)
	{
        $_SESSION['feedback_positive'][] = $feedback;
	}
	public static function addNegative($feedback)
	{
		$_SESSION['feedback_negative'][] = $feedback;
	}

	public static function getPositive()
	{
		if (isset($_SESSION['feedback_positive'])) {
		return $_SESSION['feedback_positive'];
		}
	}

	public static function getNegative()
	{
		if (isset($_SESSION['feedback_negative'])) {		
		return $_SESSION['feedback_negative'];
		}
	}
	public static function clear()
	{
		Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
	}

}