<?php
	set_time_limit(0);
	$mysqlhost = "mysql04.totaalholding.nl"; 
	$user = "patric1q_test";
	$passwd = "test1";
	//haal artiesten op
	$mysql = mysql_connect($mysqlhost, $user, $passwd);				
	$db_selected = mysql_select_db('patric1q_test', $mysql);
	$query = mysql_query("SELECT userid, artist, totalplays FROM dataset WHERE artist IN (SELECT * FROM (SELECT DISTINCT artist1 FROM correlation) as p) LIMIT 200");
	$array = array();
	$tmp = '';
	$user = array();
	while ($row = mysql_fetch_array($query)){
	//zelfde gebruiker
		if($row['userid'] == $tmp){
			$user[] = $row['artist'];
		}
		// andere gebruiker
		else{
			$array[] = $user;
			$user = array();
			$tmp = $row['userid'];
			$user[] = $row['artist'];
		}
	}
	$store = array();
	for($i = 0; $i < count($array); $i++){
		$user = array();
		for($j = 0; $j < min((count($array[$i])-1),10); $j++){
		    $query = "SELECT DISTINCT(artist2), correlation FROM correlation WHERE artist1 = '".$array[$i][$j]."' ";
            for($j = 0; $k < min((count($user)-1),10); $k++){
                    $query .= "AND NOT artist2 ='".$array[$i][$k]."' ";
            }
            $query.= "ORDER BY correlation DESC LIMIT 20";
            $sql = mysql_query($query);
            while ($row = mysql_fetch_array($sql)){
				$tmp = array();
                $tmp[] = $row['artist2'];
                $tmp[] = $row['correlation'];
                $user[] = $tmp;   
            }
		}
		$tmp = array();
		$x = min((count($user)-1),10) +1;
		$tmp[] = $array[$i][$x];
		$user[] = $tmp;
		$store[] = $user;
	}
	$matches = 0;
	for($i = 0; $i < count($store); $i++){
		for($j = 0; $j < (count($store[$i] -1)); $j++){
			if ($store[count($store[$i] -1)][0] == $store[$j][0]){
				$matches = $matches +1;
				break;
			}
		}
	}
	$p = (($matches / $count($store)) * 100);
	echo $p;
?>