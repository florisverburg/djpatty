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
	$long = 0;
	$lat = 1;
	for($i = 0; $i < count($artists); $i++){
		$event = Artist::getEvents($artists[$i][0]);
		for($j = 0; $j < count($event); $j++){
			$temp = array();
			$temp[] = $artists[$i][0];
			$temp[] = $event[$j]->getArtists();
			$temp[] = $event[$j]->getVenue()->getLocation()->getPoint();
			$cor = $artists[$i][1]/count($temp[1]);
			if ((abs($temp[2]->getLatitude() - $lat) <3) && (abs($temp[2]->getLongitude() - $long) < 3))
				$cor = $cor + 1;
			$temp[] = $cor;
			$temp[] = $event[$j];
			$events[] = $temp;
		}
	}
	function sortByOrder($a, $b) {
		if ($b[3] == $a[3])
			return 0;
		if ($b[3] < $a[3])
			return -1;
		else
			return 1;
	}
	usort($events, 'sortByOrder');
	for($i = 0; $i < count($events); $i++){
		echo $events[$i][4]->getUrl() . " " .$events[$i][0] . " " . gmdate("d-m-Y H:i", $events[$i][4]->getStartDate()) ."<br>";
	}
?>