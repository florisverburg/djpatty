<?php

require_once('MysqlDB.class.php');
require_once('DBConfig.class.php');

class Database {

	private static $db = new MysqlDB(Config::getHostName(), Config::getUser(), Config::getPassword(), Config::getDatabaseName());

	/** 
	 *	Get similar artists based on the Last.FM 360K user dataset	
	 *
	 *	@param 		string 		$artist
	 *	@return 	string[] 	Array containing the names of the artists similar to the given parameter
	 */
	public static function getSimilarArtists($artist){

	}

	/**
	 *	Get the playcount of the given artist
	 *
	 *	@param 		string 		$artist
	 *	@return 	integer 	The amount of times this artist has been played
	 */
	public static function getArtistPlaycount($artist){

	}
}