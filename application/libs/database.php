<?php

/**
 * Class DatabaseFactory
 *
 * Use it like this:
 * $database = DatabaseFactory::getFactory()->getConnection();
 *
 *
 */

class DatabaseFactory
{
	private static $factory;
	private $database;

	public static function getFactory()
	{
		if (!self::$factory) {
			self::$factory = new DatabaseFactory();
		}
		return self::$factory;
	}

	public function getConnection() {
		if (!$this->database) {
			$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
			
			try {
				$this->database = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', 
					DB_USER, DB_PASS, $options);
			} 
			catch (PDOException $e) {
	            echo 'Database connection failed: ' . $e->getMessage();
	            // End the application.
	            //die('Database connection could not be established.');                       
        	}	
		}
		return $this->database;
	}
}