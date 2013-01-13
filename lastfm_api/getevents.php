<?php
	require_once('metatune/lib/config.php');
	require_once('lastfm_api/lastfm.api.php');
	require_once("DBConfig.class.php");
	require_once("Database.class.php");
	set_time_limit(0);
	$mysqlhost = "mysql04.totaalholding.nl"; 
	$user = "patric1q_test";
	$passwd = "test1";
	//haal artiesten op
	$mysql = mysql_connect($mysqlhost, $user, $passwd);				
	$db_selected = mysql_select_db('patric1q_test', $mysql);
	$artists = array();
	$tmp = array();
	$tmp[] = 'Queen';
	$tmp[] = 1;
	$artists[] = $tmp;
	$tmp = array();
	$tmp[] = 'Red hot chili peppers';
	$tmp[] = 0.5;
	$artists[] = $tmp;	
	$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
	CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);
	$events = array();
	for($i = 0; $i < count($artists); $i++){
		$event = Artist::getEvents($artists[$i][0]);
		for($j = 0; $j < count($event); $j++){
			$tmp = array();
			$tmp[] = $artists[$i][0];
			$tmp[] = $event[$j]->getArtists();
			$tmp[] = $event[$j]->getVenue()->getLocation()->getPoint();
			$events[] = $event[$j];
			$artist = $event[$j]->getArtists();
			$cor = $artists[$i][1]/count($artist);
			echo $tmp[2]->getLatitude() . " " . $tmp[2]->getLongitude(). "<br>";
	}
	
?>