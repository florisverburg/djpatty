<!DOCTYPE HTML>

<?php
require_once("DBConfig.class.php");
require_once("Database.class.php");

$db = new Database(DBConfig::getHostName(),DBConfig::getUser(),DBConfig::getPassword(), DBConfig::getDatabaseName());

session_start();
$ingelogd = false;
$id = 0;
if(isset($_GET['id'])){	
	$id = $_GET['id'];
}
if(isset($_SESSION['username']) && isset($_SESSION['password'])){
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$user = $db->getUser($username, $password);
	if(count($user)>0){
		$ingelogd = true;
		$user = $user[0];
	}
}
?>

<html>
	<head>
		<title>Music by djpatty&trade; - Profile</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" /></noscript>
		<script src="css/5grid/jquery.js"></script>
		<script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none&amp;mobileUI.titleBarHeight=55&amp;mobileUI.openerWidth=75&amp;mobileUI.openerText=&lt;"></script>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/style-ie9.css" /><![endif]-->
		<style type="text/css">
			.bold{
				font-weight: bold;
			}
		</style>
	</head>
	<body class="subpage">
	
		<!-- Header -->
			<div id="header-wrapper">
				<header id="header" class="5grid-layout">
					<div class="row">
						<div class="12u">

							<!-- Logo -->
								<h1 class="mobileUI-site-name"><a href="index.php">djpatty</a></h1>
							
							<!-- Nav -->
								<nav class="mobileUI-site-nav">
									<a href="index.php">Homepage</a>
								<?php if(!$ingelogd){ ?>
									<a href="register.php">Sign Up</a>
									<a href="login.php">Log In</a>
								<?php } else { 
									echo '<a href="profile.php?id='.$user["id"].'">'.$user["first_name"].' '.$user["last_name"].'</a>';
									echo '<a href="logout.php">Log Out</a>'; 
								} ?>
								</nav>

						</div>
					</div>
				</header>
			</div>

		<!-- Content -->
			<div id="content-wrapper">
				<div id="content">
					<div class="5grid-layout">
						<div class="row">
							<div class="12u">
							
								<!-- Main Content -->
								<div class="5u">
									<section>
										<h3>Profile information</h3>
										<?php
											$userResult = $db->getUserInformation($id);
											$userInfo = mysql_fetch_array($userResult);
											echo "<label class='bold'>Name:</label> ".$userInfo['first_name']." ".$userInfo['last_name']."<br />";
											echo "<label class='bold'>E-mail:</label> ".$userInfo['email']."<br />";
										?>
									</section>
								</div>
								
								<div class="6u">
									<section>
										<h3>Your most listened artists<h3>
										<?php
											$plays = $db->getMostListenedArtist($id,5);
											echo "<table>";
											echo "<th>Artist</th>";
											echo "<th>Number of plays</th>";
											while($row = mysql_fetch_array($plays)){
												echo "<tr>";
												echo "<td><a href='artist.php?uri=".$row['artist_uri']."'>".$row['artist_name']."</a></td>";
												echo "<td>".$row['plays']."</td>";
												echo "</tr>";
											}
											echo "</table>";
										?>
									</section>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- Copyright -->
			<div id="copyright">
				&copy; djpatty.
			</div>

	</body>
</html>