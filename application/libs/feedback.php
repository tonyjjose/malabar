<?php 

class Feedback
{
	public static $feedback_pos = null;
	public static $feedback_neg = null;

	public static function addPositive($feedback)
	{
		echo "addPositive";
        $_SESSION['feedback_positive'][] = $feedback;
		echo "addPositive";
	}
	public static function addNegative($feedback)
	{
		echo "addnegitive";
		$_SESSION['feedback_negative'][] = $feedback;
	}

	public static function getPositive()
	{
		echo "getPositive";
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
	public static function clearFeedback()
	{
		self::$feedback_pos = null;
		self::$feedback_neg = null;
	}
	public static function clear()
	{
		Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
	}

}