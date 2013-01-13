<?php
require_once("DBConfig.class.php");
require_once("Database.class.php");

	$db = new Database(DBConfig::getHostName(),DBConfig::getUser(),DBConfig::getPassword(), DBConfig::getDatabaseName());

	function cleanInput($value){
		$value = mysql_real_escape_string($value);
		return $value;
	}
	
	$username = cleanInput($_POST['username']);
	$password = sha1(cleanInput($_POST['password']));
	$firstname = cleanInput($_POST['first-name']);
	$lastname = cleanInput($_POST['last-name']);

	$status = $db->registerUser($username,$password,$firstname,$lastname);

	header('location: register.php?success='.$status);
?>
