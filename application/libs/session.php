<?php

/**
 * Session wrapper class
 *
 * Handles the session stuff. Creates session when no one exists, sets and
 * gets values, and closes the session properly. 
 */
class Session
{
    /**
     * Starts the session
     */
    public static function init()
    {
        // if no session exist, start the session
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * Sets a specific value to a specific key of the session
     * @param mixed $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets/returns the value of a specific key of the session
     * @param mixed $key Usually a string, right ?
     * @return mixed
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    /**
     * Adds a value as a new array element to the key.
     * Useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     */
    public static function add($key, $value)
    {
        $_SESSION[$key][] = $value;
    }    

    /**
     * Deletes the session (= logs the user out)
     */
    public static function destroy()
    {
        //$_SESSION = array();
        session_destroy();
    }
}
