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
	 *	@param 		int 		The number of results
	 *	@return 	string[] 	Array containing the names of the artists similar to the given parameter
	 */
	public function getSimilarArtists($artist,$limit){
		$query = "SELECT artist2 FROM correlation WHERE artist1 = '".$artist."' ORDER BY correlation DESC LIMIT ".$limit;
		
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_array($result)){
			$array[]= $row;
		}
		return $array;
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

	public function getUser($username, $password){
		$query = "SELECT * FROM users WHERE email='".$username."' AND password='".$password."'";
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_array($result)){
			$array[]= $row;
		}
		return $array;
	}

	public function addPlay($userid, $artist, $artisturi){
		$query = "SELECT * FROM plays WHERE user_id=".$userid." AND artist_uri='".$artisturi."'";
		$result = mysql_query($query);

		if(mysql_num_rows($result) < 1){
			$query = "INSERT INTO plays (user_id, artist_name, artist_uri, plays) VALUES (".$userid.",'".$artist."','".$artisturi."', 1)";
		}
		else {
			$query = "UPDATE plays SET plays = plays + 1 WHERE user_id=".$userid." AND artist_uri='".$artisturi."'";
		}

		$result = mysql_query($query);
		return $result;
	}

	public function registerUser($username,$password,$firstname,$lastname){
		$query = "INSERT INTO users (email,password,first_name,last_name) VALUES ('".$username."','".$password."','".$firstname."','".$lastname."')";
		
		// There is a Unique constraint on the username, so the query will return false if that username is already taken.
		return mysql_query($query);
	}
}