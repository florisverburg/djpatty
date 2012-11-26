<?php

class DBConfig {

	const HOST_NAME = "mysql03.totaalholding.nl";
	const USER_NAME = "sander2b_djpatty";
	const PASSWORD  = "djpatty";
	const DB_NAME 	= "sander2b_djpatty";

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