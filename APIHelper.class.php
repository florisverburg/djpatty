<?php

require_once('lastfm_api/lastfm.api.php');
require_once('metatune/lib/config.php');

class APIHelper {

	public static function getEventRecommendations($artists){
		$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
		CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);
		$spotify = MetaTune::getInstance();

		
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