<?php 

/**
 * Feedback handler class
 *
 * Simple abstraction for feedbacks. we just store them in Session variables, set them and
 * return them with static methods
 */
class Feedback
{
	/**
	 * Sets the feedback to session variable
	 */
	public static function addPositive($feedback)
	{
        $_SESSION['feedback_positive'][] = $feedback;
	}

	public static function addNegative($feedback)
	{
		$_SESSION['feedback_negative'][] = $feedback;
	}

	/**
	 * Gets the feedback from session variable
	 * @return array the feedbacks
	 */
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

	/**
	 * Clears the feedback session variable.
	 * This is typically done after displaying each view->render
	 */
	public static function clear()
	{
		Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
	}

	/**
	 * Dump the feedbacks for debugging 
	 */
	public static function printAll()
	{
		print_r(Feedback::getPositive());
		print_r(Feedback::getNegative());		
	}
}