<?php
	require_once('metatune/lib/config.php');
	require_once('lastfm_api/lastfm.api.php');
	require_once('Database.class.php');
	require_once('DBConfig.class.php');

	$spotify = MetaTune::getInstance();
	
	$db = new Database(DBConfig::getHostName(),DBConfig::getUser(),DBConfig::getPassword(), DBConfig::getDatabaseName());
	if(isset($_REQUEST['artist'])){
		$artist=$_REQUEST['artist'];
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Title Goes Here</title>
		<link rel="stylesheet/less" type="text/css" href="less/djpatty.less" />

	</head>
	<body>
		<div class="content">
			<?php 
				$results = $db->getSimilarArtists($artist);
				foreach($results as $result){
					echo $result."<br />";
				}
			?>
		</div>


		<!-- INCLUDE SCRIPTS -->
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.js" type="text/javascript"></script>
		<script src="js/less.js" type="text/javascript"></script>
	</body>
</html>