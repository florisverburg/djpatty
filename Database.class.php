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
		$query = "SELECT DISTINCT artist2 FROM correlation WHERE artist1 = '".$artist."' ORDER BY correlation DESC LIMIT ".$limit;
		
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_array($result)){
			$array[]= $row;
		}
		return $array;
	}

	public function getRecommendations($id){
		$sql = mysql_query("SELECT artist_name FROM plays where user_id = ".$id." AND plays > 1 ORDER BY plays DESC LIMIT 10");
        $artists = array();
        while ($row = mysql_fetch_array($sql)){
                $artists[] = $row['artist_name'];
        }
        $cors = array();
        for($i = 0; $i < count($artists); $i++){
               
                $query = "SELECT DISTINCT(artist2), correlation FROM correlation WHERE artist1 = '".$artists[$i]."' ";
                for($j = 0; $j < count($artists); $j++){
                        $query .= "AND NOT artist2 ='".$artists[$j]."' ";
                }
                $query.= "ORDER BY correlation DESC LIMIT 20";
                $sql = mysql_query($query);
                while ($row = mysql_fetch_array($sql)){
                        $tmp = array();
                        $tmp[] = $row['artist2'];
                        $tmp[] = $row['correlation'];
                        $cors[] = $tmp;
                       
                }
        }
        $values = array();
        for($i = 0; $i < count($cors); $i++){
                $temp = $cors[$i][0];
                $can = true;
                for($x = 0; $x < count($values); $x++){
                        if ($values[$x][0] == $temp){
                                $can = false;
                                break;
                        }
                }
                if($can){
                        $value = 0;
                        for($j = 0; $j < count($cors); $j++){
                                if ($cors[$j][0] == $temp)
                                        $value += $cors[$j][1];
                        }
                        $tmp = array();
                        $tmp[] = $temp;
                        $tmp[] = $value;
                        $values[] = $tmp;
                }
        }
        function sortByOrder($a, $b) {
                if ($b[1] == $a[1])
                        return 0;
                if ($b[1] < $a[1])
                        return -1;
                else
                        return 1;
        }
        usort($values, 'sortByOrder');

        return $values;
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

	public function getMostListenedArtist($id, $limit){
		$query = "SELECT * FROM plays WHERE user_id = ".$id." ORDER BY plays DESC LIMIT ".$limit;
		$result = mysql_query($query);

		return $result;
	}

	public function getUserInformation($id){
		$query = "SELECT * FROM users WHERE id = ".$id;
		$result = mysql_query($query);

		return $result;
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