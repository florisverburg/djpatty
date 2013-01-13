<?php

	$mysqlhost = "mysql04.totaalholding.nl"; 
	$user = "patric1q_test";
	$passwd = "test1";
	
	$mysql = mysql_connect($mysqlhost, $user, $passwd);
	if (!$mysql) {
		die('Could not connect: ' . mysql_error());
	}
	
	$db_selected = mysql_select_db('patric1q_test', $mysql);
	if (!$db_selected) {
		die('Could not connect: ' . mysql_error());
	}
?>