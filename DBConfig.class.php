<?php

class DBConfig {
	
	private static $DB_HOST = "mysql03.totaalholding.nl";
	private static $DB_USER = "sander2b_djpatty";
	private static $DB_PASSWORD = "djpatty";
	private static $DB_NAME = "sander2b_djpatty";

	public static function getHostName(){
		return $DB_HOST;
	}

	public static function getHostName(){
		return $DB_USER;
	}

	public static function getHostName(){
		return $DB_PASSWORD;
	}

	public static function getHostName(){
		return $DB_NAME;
	}
}