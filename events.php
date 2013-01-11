<!DOCTYPE HTML>
<!--
	Halcyonic 2.0 by HTML5 Up!
	html5up.net | @nodethirtythree
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<?php
	require_once('metatune/lib/config.php');
	require_once('lastfm_api/lastfm.api.php');
	require_once("DBConfig.class.php");
	require_once("Database.class.php");
	error_reporting(0);

	$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
	CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

	$spotify = MetaTune::getInstance();
	if(isset($_REQUEST['artist'])){
		$uri = $_REQUEST['artist'];
		$artist = $spotify->lookupArtist($uri,true);
		$lastfmArtist = Artist::getInfo($artist);
		$artistpop = $spotify->searchArtist($artist->getName());
		$artistpop = $artistpop[0];
	}
	else {
	}
	$db = new Database(DBConfig::getHostName(),DBConfig::getUser(),DBConfig::getPassword(), DBConfig::getDatabaseName());

	session_start();
	$ingelogd = false;
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
		<title>Upcoming events for <?php echo $artist->getName(); ?> on djpatty</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" /></noscript>
		<script src="css/5grid/jquery.js"></script>
		<script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none&amp;mobileUI.titleBarHeight=55&amp;mobileUI.openerWidth=75&amp;mobileUI.openerText=&lt;"></script>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/style-ie9.css" /><![endif]-->
		<script>
			window.onload = function(){
				$('.description').slideToggle();
				$('.eventheader').click(function(){
					var id = '#d' + $(this).attr('id');
          			$(id).slideToggle();
				})
			};
		</script>
	</head>
	<body class="subpage">

		<!-- Header -->
			<div id="header-wrapper">
				<header id="header" class="5grid-layout">
					<div class="row">
						<div class="12u">

							<!-- Logo -->
								<h1 class="mobileUI-site-name"><a href="/">djpatty</a></h1>
							
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
							<div class="9u">
								
								<!-- Main Content -->
									<section>
										<header>
											<h2>Upcoming events for <?php echo $artist->getName() ?></h2>
										</header>
										<?php 
											$events = Artist::getEvents($lastfmArtist->getName());
										?>
										<table id="events">
												<thead>
													<tr>
														<th>Title</th>
														<th>Location</th>
														<th>Date</th>
														<th>Time</th>
											<?php		
														$col = 1;
														foreach($events as $event){
															$venue = $event->getVenue();
															$location = $venue->getLocation();
															$class = "backcol1";
															$class2 = "col1";
															if($col%2 == 0){
																$class = "backcol2";
																$class2 = "col2";
															}
															echo "<tr id='".$event->getId()."' class='eventheader ".$class."' >";
															echo "<td>".$event->getTitle()."</td>";
															echo "<td>".$venue->getName().", ".$location->getCity().", ".$location->getCountry()."</td>";
															echo "<td>".date("d-m-Y",$event->getStartDate())."</td>";
															echo "<td>".date("H:i",$event->getStartDate())."</td>";
															echo "</tr>";
															echo "<tr id='d".$event->getId()."' class='description ".$class." ".$class2."'>";
																$image = $event->getImage(2);
																echo "<td colspan='4'>"."<div class='descriptionimage'><img src='".$image."'></div><div class='descriptiontext'>";
																if($event->getDescription()!=null){
																	echo "<h3>Description</h3>".$event->getDescription();
																}
																else{
																	echo "No description available";
																}
																echo "<p><a href='".$event->getUrl()."' target='_blank'>Event on last.fm</a></p>";
																echo "</div></td>";
															echo "</tr>";
															$col++;
														}	
											?>
										</table>

									</section>	

							</div>
							<div class="3u">
								
								<!-- Sidebar -->
									<section>
										<header>
											<h2>Artist Info</h2>
										</header>
										<?php
											$image = $lastfmArtist->getLargeImage();
											if($image){
												echo "<img src='".$image."'>";
											}
										?>
										<ul class="link-list">
											<li><label class="eventinfo">Name:</label> <?php echo $artist->getName() ?></li>
											<li><label class="eventinfo">Spotify score:</label> <?php echo $artistpop->getPopularityAsPercent() ?>%</li>
											<?php
												$members = $lastfmArtist->getBandmembers();
												if($members != null){
													echo "<li><label class='eventinfo'>Bandmembers: </label>".implode(', ',$members).".</li>";
												}
											?>
										</ul>
										<?php echo "<a href='artist.php?uri=".$artist->getURI()."'>See more</a>"; ?>
									</section>

							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- Footer -->

		<!-- Copyright -->
			<div id="copyright">
				&copy; djpatty.
			</div>

	</body>
</html>