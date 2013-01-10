<?php

require_once('lastfm_api/lastfm.api.php');
require_once('metatune/lib/config.php');

class APIHelper {


	public static function getEventRecommendations($artists){
		$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
		CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);
		$spotify = MetaTune::getInstance();

		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		 { 
	        $rip = getenv("HTTP_CLIENT_IP"); 
	     } 
	     else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
	     { 
	        $rip = getenv("HTTP_X_FORWARDED_FOR"); 
	     } 
	     else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
	     { 
	        $rip = getenv("REMOTE_ADDR"); 
	     } 
	     else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
	     { 
	        $rip = $_SERVER['REMOTE_ADDR']; 
	     } 
	     else 
	     { 
	        $rip = "unknown"; 
	     }

	     $tags = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$rip);
	     $lat = $tags['latitude'];
	     $long = $tags['longitude'];
			
		$events = array();
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
		function sortByOrder2($a, $b) {
			if ($b[3] == $a[3])
				return 0;
			if ($b[3] < $a[3])
				return -1;
			else
				return 1;
		}
		usort($events, 'sortByOrder2');
		return $events;
	}

}