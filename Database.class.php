<?php

require_once('lastfm_api/lastfm.api.php');

class Database {

	private $LAST_FM_API_KEY;

	private $db;

	public function __construct($host,$user,$password,$dbname){
		$db = mysql_connect($host,$user,$password);
		mysql_select_db($dbname,$db);
		$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
		CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);
	}
	/** 
	 *	Get similar artists based on the Last.FM 360K user dataset	
	 *
	 *	@param 		string 		$artist
	 *	@return 	string[] 	Array containing the names of the artists similar to the given parameter
	 */
	public function getSimilarArtists($artist){
		$genres = Artist::getTags($artist);
		$query = "SELECT * FROM dataset WHERE artist = '".strtolower($artist)."' ORDER BY numberofplays";
		$result = mysql_query($query);

		$userArray = array();
		while($row = mysql_fetch_array($result)){
			$userArray[] = $row;
		}

		$similarArtists = array();

		foreach($userArray as $user){
			$query = "SELECT artist FROM dataset WHERE userid = '".$user['userid']."' ORDER BY numberofplays LIMIT 5";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)){
				if(!in_array($row['artist'], $similarArtists))
					$tags = Artist::getTags($row['artist']);
					$similar = false;
					foreach($tags as $tag){
						if(in_array($tag->getName(), $genres)){
							$similar = true;
							break;
						}
					}
					if($similar)
						$similarArtists[] = $row['artist'];
			}
		}

		return $similarArtists;
	}

	/**
	 *	Get the playcount of the given artist
	 *
	 *	@param 		string 		$artist
	 *	@return 	integer 	The amount of times this artist has been played
	 */
	public function getArtistPlaycount($artist){
		return 0;
	}

	public function getUsers(){
		$query = "SELECT * FROM users";
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_array($result)){
			$array[]= $row;
		}
		return $array;
	}
}