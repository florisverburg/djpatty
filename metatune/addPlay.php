<?php
require_once("DBConfig.class.php");
require_once("Database.class.php");

	$db = new Database(DBConfig::getHostName(),DBConfig::getUser(),DBConfig::getPassword(), DBConfig::getDatabaseName());

	function cleanInput($value){
		$value = mysql_real_escape_string($value);
		return $value;
	}
	
	$artist = cleanInput($_REQUEST['artist']);
	$artisturi = cleanInput($_REQUEST['artisturi']);

	session_start();
	$ingelogd = false;
	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		$user = $db->getUser($username, $password);
		if(count($user)>0){
			$ingelogd = true;
			$user = $user[0];
		}
	}

	if($ingelogd){
		if($db->addPlay($user['id'], $artist, $artisturi)){
			return true;
		}
		else return false;
	}	
?>
