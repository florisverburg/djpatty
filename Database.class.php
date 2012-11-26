<?php

class Database {

	private $db;

	public function __construct($host,$user,$password,$dbname){
		$db = mysql_connect($host,$user,$password);
		mysql_select_db($dbname,$db);
	}
	/** 
	 *	Get similar artists based on the Last.FM 360K user dataset	
	 *
	 *	@param 		string 		$artist
	 *	@return 	string[] 	Array containing the names of the artists similar to the given parameter
	 */
	public function getSimilarArtists($artist){
		return true;
	}

	/**
	 *	Get the playcount of the given artist
	 *
	 *	@param 		string 		$artist
	 *	@return 	integer 	The amount of times this artist has been played
	 */
	public function getArtistPlaycount($artist){

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