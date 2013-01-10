<?php
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
		<title>Music by djpatty&trade;</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" /></noscript>
		<script src="css/5grid/jquery.js"></script>
		<script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none&amp;mobileUI.titleBarHeight=55&amp;mobileUI.openerWidth=75&amp;mobileUI.openerText=&lt;"></script>
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
									<?php
										$artistrecommendations = $db->getRecommendations($user['id']);
										if(count($artistrecommendations)>0){
											echo "<ul class='link-list'>";
											for($i = 0; $i < min(count($artistrecommendations),5); $i++){
												$artist = $spotify->searchArtist($artistrecommendations[$i][0])[0];
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
								</section>
							</div>
							<div class="6u" >
								<section>
									<header>
										<h2>Events</h2>
									</header>
									<?php
										echo "<p>We're sorry, no recommendations could be found.</p>";
									?>
								</section>
							</div>
						<?php 
									// end if($isingelogd)
									} 
									else {
										
										// TODO: ADD SOMETHING

									}
								?>
									
								
						</div>
						<div class="row">
							<div class="4u">

								<!-- Box #1 -->
									<section>
										<header>
											<h2>Zanger Rinus</h2>
											<h3>How djpatty changed his life.</h3>
										</header>
										<a href="#" class="feature-image"><img src="images/rinus_header.jpg" alt="" /></a>
										<p>
											Vroeger, toen alles beter was en muziek nog ouderwets op de acordeon bespeeld werd zonder autotune, was het intranet traag en onoverzichtelijk. Aanbiedingen van bananen waren lastig te vinden, om nog maar niet te spreken over goede artiesten! Grappig eigenlijk, dat djpatty al deze problemen oplost waardoor artiesten zoals ik; die nog steeds old-skool hipster muziek maken met bananen en acordeons + ronnie nu OOK gemakkelijk aan luisteraars komen. djpatty heeft mijn leven veranderd!
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
											<li>FREE music streaming (oh r'ly? - yes really)</li>
											<li>A wide variaty of FREE music (- yes really)</li>
											<li>Recommendation based on your activity(oh, yes WE CAN!)</li>
											<li>No free cookies (we already ate them)</li>
											<li>Features bananas + Rinus plus Ronnie</li>
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
												<img src="images/patty.png" alt="" />
												<p>"Wow, now <u>that</u> is what I call awesomeeee"</p>
												<span>Patty, DJ</span>
											</li>
											<li>
												<img src="images/nobodycares.jpg" alt="" />
												<p>"Houdoe!"</p>
												<span>Floris Verburg, Brabander</span>
											</li>
											<li>
												<img src="images/rinus.jpg" alt="" />
												<p>"Eet veel bananen, bananen zijn gezond!"</p>
												<span>Rinus, Zanger</span>
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