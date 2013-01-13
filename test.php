<?php
	set_time_limit(0);
	$mysqlhost = "mysql04.totaalholding.nl"; 
	$user = "patric1q_test";
	$passwd = "test1";
	//haal artiesten op
	$mysql = mysql_connect($mysqlhost, $user, $passwd);				
	$db_selected = mysql_select_db('patric1q_test', $mysql);
	$sql = mysql_query("SELECT artist_name FROM plays where user_id = 1 AND plays > 1 ORDER BY plays DESC LIMIT 10");
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
	
	for($i = 0; $i < 5; $i++){
		echo $values[$i][0]."<br>";
	}
	
?>
