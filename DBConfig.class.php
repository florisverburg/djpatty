<?php

class DBConfig {

	const HOST_NAME = "sql.ewi.tudelft.nl";
	const USER_NAME = "djpatty";
	const PASSWORD  = "testtest";
	const DB_NAME 	= "djpatty";

	public static function getHostName(){
		return self::HOST_NAME;
	}

	public static function getUser(){
		return self::USER_NAME;
	}

	public static function getPassword(){
		return self::PASSWORD;
	}

	public static function getDatabaseName(){
		return self::DB_NAME;
	}
}