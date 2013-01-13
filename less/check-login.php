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

	$userArray = $db->getUser($username,$password);

	if(count($userArray) > 0){
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['id'] = $userArray[0]['id'];
		header('location: index.php');
	}
	else{
		header('location: login.php?success=false');
	}	
?>
