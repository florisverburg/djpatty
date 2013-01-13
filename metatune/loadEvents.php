<?php

	require_once('metatune/lib/config.php');
	require_once('lastfm_api/lastfm.api.php');
	require_once("APIHelper.class.php");
	require_once("DBConfig.class.php");
	require_once("Database.class.php");

	$db = new Database(DBConfig::getHostName(),DBConfig::getUser(),DBConfig::getPassword(), DBConfig::getDatabaseName());

	$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
	CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

	$spotify = MetaTune::getInstance();
		
	if(isset($_GET['userid'])){
		$artistrecommendations = $db->getRecommendations($_GET['userid']);
		$events = APIHelper::getEventRecommendations($artistrecommendations);
		$artistInList = array();
		$contains = 0;
		$echo = "";
		if(count($events)>0){
		$echo .= "<ul class='link-list'>";
			for($i = 0; $i < count($events); $i++){
				if ($contains > 4){
					break;
				}
				if(!in_array($events[$i][0],$artistInList)){
					$contains++;
					$echo .= "<li>";
					$echo .= "<a target='_BLANK' href='".$events[$i][4]->getUrl()."'>".$events[$i][4]->getTitle()."</a>";
					$echo .= "<span class='tags'>".date("d-m-Y",$events[$i][4]->getStartDate())."</span>";
					$echo .= "</li>";
					$artistInList[] = $events[$i][0];
				}
			}
			$echo .= "</ul>";
		}
		else $echo .= "<p>We're sorry, no recommendations could be found.</p>";
		echo $echo;
	}
	else
		echo "<p>Event recommendations could not be loaded</p>";

?>