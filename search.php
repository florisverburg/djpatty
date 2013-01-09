<!DOCTYPE HTML>
<!--
	Halcyonic 2.0 by HTML5 Up!
	html5up.net | @nodethirtythree
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<?php
	require_once('metatune/lib/config.php');


	$spotify = MetaTune::getInstance();
	if(isset($_REQUEST['q'])){
		$query = $_REQUEST['q'];
		$page = $_REQUEST['page'];
	}
	
	require_once("DBConfig.class.php");
	require_once("Database.class.php");

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
		<title>Search results for '<?php echo $query ?>'</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/djpatty.css" /><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" />
			</noscript>
		<script src="css/5grid/jquery.js"></script>
		<script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none&amp;mobileUI.titleBarHeight=55&amp;mobileUI.openerWidth=75&amp;mobileUI.openerText=&lt;"></script>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/style-ie9.css" /><![endif]-->
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
									<section>
										
										<h2>Search results for '<?php echo $query; ?>'</h2>
										<br />
										<div class="artistresults">
											<h3>Artists</h3>
											<?php
													$artists = $spotify->searchArtist($query);

														if(count($artists)>0){
											?>
											<table>
												<thead>
													<tr>
														<th>Artist</th>
														<th>Score</th>
											<?php	
															foreach($artists as $artist){
																if($artist->getPopularityAsPercent() > 0){
																	echo "<tr>";
																	echo "<td><a href='artist.php?uri=" . $artist->getURI() . "'>" . $artist->getName() . "</a></td>";
																	echo "<td>".$artist->getPopularityAsPercent()."%</td>";
																	echo "</tr>";
																}
															}	
											?>
											</table>
											<?php
														}
														else echo "<p>No search results</p>";
											?>
										</div>

										<br />

										<div class="trackresults">
											<h3>Tracks</h3>
											<?php
												$tracks = $spotify->searchTrack($query, $page);

												if(count($tracks)>0){
											?>
											<table>
												<thead>
													<tr>
														<th>Title</th>
														<th>Artist</th>
														<th>Album</th>
														<th>Duration</th>
													</tr>
												</thead>
												<tbody>
													<?php
															foreach($tracks as $track){
																$artist = $track->getArtist();
																echo "<tr>";
																echo "<td><a href='" . $track->getURL() . "'>" . $track->getTitle() . "</a></td>";
																echo "<td><a href='artist.php?uri=" . ((is_array($artist)) ? $artist[0]->getURI() : $artist->getURI()) . "'>" . $track->getArtistAsString() . "</a></td>";
																echo "<td><a href='" . $track->getAlbum()->getURL() . "'>" . $track->getAlbum() . "</a></td>";
																echo "<td>" . $track->getLengthInMinutesAsString() . "</td>";
																echo "</tr>";
															}
														}
														else echo "<p>No search results</p>";
														
													?>
												</tbody>
											</table>
										</div>

										<ul id="pagin">
											<?php
												if($page > 1){
													$page2 = $page - 1;
													$query = str_replace(" ", "+", $query);
													echo "<li><a href=search.php?page=".$page2."&q=".$query.">previous</a></li>";
												}
												else{
													echo "<li><a id='hidden'>previous</a></li>";
												}
												if($spotify->getNumberOfPages($query) > 1){
											?>
											<li><a class="current" ><?php echo $page; ?></a></li>
											<?php
												}
												if($page < $spotify->getNumberOfPages($query)){
													$page2 = $page + 1;
													$query = str_replace(" ", "+", $query);
													echo "<li><a href=search.php?page=".$page2."&q=".$query.">next</a></li>";
												}
											?>
										</ul>

									</section>

							</div>
						</div>
					</div>
				</div>
			</div>


		<!-- Copyright -->
			<div id="copyright">
				&copy; djpatty. All rights reserved.
			</div>

	</body>
</html>