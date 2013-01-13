<?php

class DBConfig {

	const HOST_NAME = "mysql04.totaalholding.nl";
	const USER_NAME = "patric1q_test";
	const PASSWORD  = "test1";
	const DB_NAME 	= "patric1q_test";

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