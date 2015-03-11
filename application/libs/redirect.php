<?php

/**
 * Redirect to specified URL
 *
 * Simple abstraction for redirecting the user to a certain page.
 */
class Redirect
{
	/**
	 * Redirect to user's home.
	 * To the home(ie default page) page of the respective user type.
	 * Eg: for student this will be ..app/student/index 
	 *
	 */
	public static function home()
	{
		$user_type = Session::get('user_type');

		if ($user_type == ROLE_MANAGER) {
			header('location: ' . URL . 'manager/index');
		} elseif ($user_type == ROLE_INSTRUCTOR) {
			header('location: ' . URL . 'instructor/index');
		} elseif ($user_type == ROLE_STUDENT) {
			header('location: ' . URL . 'student/index');
		} else {
			//well no type, let him login to get a type. :-D
			header('location: ' . URL . 'login');
		}
	}

	/**
	 * To the defined page
	 *
	 * @param $path
	 */
	public static function to($path)
	{
		header('location: ' . URL . $path);
	}
}