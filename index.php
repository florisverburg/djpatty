<?php
require_once("DBConfig.class.php");
require_once("Database.class.php");
require_once("APIHelper.class.php");
error_reporting(0);

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

require_once('metatune/lib/config.php');
require_once('lastfm_api/lastfm.api.php');

$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

$spotify = MetaTune::getInstance();

?>

<!DOCTYPE HTML>
<!--
	Halcyonic 2.0 by HTML5 Up!
	html5up.net | @nodethirtythree
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<link id="page_favicon" href="favicon.ico" rel="icon" type="image/x-icon">
		<title>Music by djpatty&trade;</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" /></noscript>
		<script src="css/5grid/jquery.js"></script>
		<script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none&amp;mobileUI.titleBarHeight=55&amp;mobileUI.openerWidth=75&amp;mobileUI.openerText=&lt;"></script>
		<script src="js/prototype.js"></script>
		<script src="js/djpatty.js"></script>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/style-ie9.css" /><![endif]-->
	</head>
	<body>

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
							<div class="12u">
								<center>
									<div id="search">
										<form method = "get" action="search.php">
											<input type="hidden" name="page" value="1" />
											<input type="text" placeholder="Search..." name="q" style="width:50%;"></a>  
										</form>
									</div>
								</center>
							</div>
						</div>

						<?php
							if($ingelogd){
						?>
						<div class="row">
							<div class="12u">
								<center>
									<header>
										<h2>Recommended for you</h2>
										<h3>Based on the artists you listened to.</h3>
									</header>
								</center>
							</div>
						</div>
						<div class="row" >
							<div class="6u">
								<section>
									<header>
										<h2>Artists</h2>
									</header>
									<div>
										<?php
											$artistrecommendations = $db->getRecommendations($user['id']);
											if(count($artistrecommendations)>0){
												echo "<ul class='link-list'>";
												for($i = 0; $i < min(count($artistrecommendations),5); $i++){
													$artist = $spotify->searchArtist($artistrecommendations[$i][0]);
													$artist = $artist[0];
													$lastfmArtist = Artist::getInfo($artistrecommendations[$i][0]);
													$tags = $lastfmArtist->getArtistTags();
													$tagstring = "tagged as ";
													foreach($tags as $tag){
														$tagstring .= $tag->getName().", ";
													}

													echo "<li>";
													echo "<a href='artist.php?uri=".$artist->getURI()."'>".$artist->getName()."</a>";
													echo "<span class='tags'>".substr($tagstring, 0, strlen($tagstring)-2)."</span>";
													echo "</li>";
												}
												echo "</ul>";
											}
											else echo "<p>We're sorry, no recommendations could be found.</p>";
										?>
									</div>
								</section>
							</div>
							<div class="6u" >
								<section>
									<header>
										<h2>Events</h2>
									</header>
									<div id="<?php echo $user['id']?>" class="recommendEvents">
										<div id="load"><center><img src="images/loading.gif" /></center></div>
									</div>
								</section>
							</div>
						</div>
						<?php 
									// end if($isingelogd)
									} 
									else {
										
										// TODO: ADD SOMETHING

									}
								?>
									
								
						
						<div class="row">
							<div class="4u">

								<!-- Box #1 -->
									<section>
										<header>
											<h2>Recommendations</h2>
											<h3>Find artists you'd never find yourself!</h3>
										</header>
										<p>
											Ever found yourself listening to the same old artist over and over again for hours? We know we did! Upon registering at djpatty, we keep track of every artist you check out on artist-pages and what tracks you listened to on their artist page. Based on your listening behaviour both artists and events that match your interests can be found on your home-page. It is thát easy! Why don't you sign up right now, it's FREE!
										</p>
									</section>

							</div>
							<div class="4u">

								<!-- Box #2 -->
									<section>
										<header>
											<h2>What We Do</h2>
											<h3>Because we're <u>that</u> awesome.</h3>
										</header>
										<ul class="check-list">
											<li>A wide variaty of music</li>
											<li>Fast searching for albums, artists ánd tracks</li>
											<li>Detailed artist-pages with related artists and upcoming events</li>
											<li>Recommends artists and events based on your listening-behaviour</li>
											<li>Fully integrated with spotify</li>
										</ul>
									</section>

							</div>
							<div class="4u">
								
								<!-- Box #3 -->
									<section>
										<header>
											<h2>What People Are Saying</h2>
											<h3>It's legen... wait for it... DARY.</h3>
										</header>
										<ul class="quote-list">
											<li>
												<img src="images/obama.jpg" alt="" />
												<p>"Oh yes, they can!"</p>
												<span>Barack Obama, President</span>
											</li>
											<li>
												<img src="images/skrillex.jpg" alt="" />
												<p>"WOBWOBBWOBOWBOWB wopwopwop WOBWOBWOB"</p>
												<span>Skrillex, Dubstep Producer</span>
											</li>
											<li>
												<img src="images/floris.jpg" alt="" />
												<p>"Hoe hedde ge da gedoan?"</p>
												<span>Floris Verburg, Brabander</span>
											</li>
										</ul>
									</section>

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