<!DOCTYPE HTML>
<!--
	Halcyonic 2.0 by HTML5 Up!
	html5up.net | @nodethirtythree
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
	require_once('metatune/lib/config.php');
	require_once('lastfm_api/lastfm.api.php');

	$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
	CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

	$spotify = MetaTune::getInstance();
	if(isset($_REQUEST['uri'])){
		$uri = $_REQUEST['uri'];
		$artist = $spotify->lookupArtist($uri,true);
		$lastfmArtist = Artist::getInfo($artist);
	}
	else {
	}
?>

<html>
	<head>
		<title><?php echo $artist->getName(); ?> on djpatty</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" /></noscript>
		<script src="css/5grid/jquery.js"></script>
		<script src="css/5grid/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none&amp;mobileUI.titleBarHeight=55&amp;mobileUI.openerWidth=75&amp;mobileUI.openerText=&lt;"></script>
		<script src="js/djpatty.js"></script>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/style-ie9.css" /><![endif]-->
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
									<a href="index.html">Homepage</a>
									<a href="threecolumn.html">Three Column</a>
									<a href="twocolumn1.html">Two Column #1</a>
									<a href="twocolumn2.html">Two Column #2</a>
									<a href="onecolumn.html">One Column</a>
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
											<h2><?php echo $artist->getName(); ?></h2>
											<h3>Spotify score: <?php echo $artist->getPopularityAsPercent()."%"; ?></h3>
											<p class="bio"><?php echo $lastfmArtist->getBiography(); ?></p>
										</header>

										<?php
											$duplicateAlbums = array();
											$lastfmdata = Artist::getTopAlbumNames($artist->getName());
											$albums = $artist->getAlbum();

											// For testing purposes we limit the amount of albums, to speed things up. 
											for($i = 0; $i < 5; $i++){
												if(in_array($albums[$i]->getName(), $lastfmdata) && !in_array($albums[$i]->getName(), $duplicateAlbums)){
													$duplicateAlbums[] = $albums[$i]->getName();
													$lastfmAlbum = Album::getInfo($artist->getName(),$albums[$i]->getName());
										?>
										<div class="album">
											<h3><?php echo $albums[$i]->getName()." (".$albums[$i]->getRelease().")"; ?></h3>
											<div class="albumArt" id="<?php echo $albums[$i]->getURI(); ?>">
												<img src="<?php echo $lastfmAlbum->getImage(Media::IMAGE_LARGE); ?>" />
											</div>
											<div class="tracklist">
												<table>
													<thead>
														<tr>
															<th class="tracknumber"></th>
															<th>Title</th>
															<th class="duration">Duration</th>
														</tr>
													</thead>
													<tbody>
											<?php
														$spotifyAlbum = $spotify->lookupAlbum($albums[$i]->getURI(),true);
														$tracks = $spotifyAlbum->getTracks();
														$i = 1;
														foreach($tracks as $track){
															echo "<tr class='track' data-original-title='test' data-album='".$track->getAlbum()->getURI()."' id='".$track->getURI()."''>";
															echo "<td>".$i."</td>";
															echo "<td>".$track->getTitle()."</td>";
															echo "<td>".$track->getLengthInMinutesAsString()."</td>";
															echo "</tr>";
															$i++;
														}
											?>
												</table>
											</div>
										</div>
										<?php
												}
											}
										?>
										<div class="album"></div>
									</section>

							</div>
							<div class="3u">
								
								<!-- Sidebar -->
									<section>
										<header>
											<h2>Related Artists</h2>
										</header>
										<ul class="link-list">
											<li><a href="#">Sed dolore viverra</a></li>
											<li><a href="#">Ligula non varius</a></li>
											<li><a href="#">Nec sociis natoque</a></li>
											<li><a href="#">Penatibus et magnis</a></li>
											<li><a href="#">Dis parturient montes</a></li>
											<li><a href="#">Nascetur ridiculus</a></li>
										</ul>
									</section>
									<section>
										<header>
											<h2>Upcoming events</h2>
											<p>
												See where <?php echo $artist->getName(); ?> is playing.					
											</p>
										</header>
										<ul class="link-list events">
											<?php 
												$events = Artist::getEvents($lastfmArtist->getName());
												if(sizeof($events)>0){
													for($i = 0; $i < min(sizeof($events),5); $i++){
														echo "<li><a href='".$events[$i]->getUrl()."'>".date("F j, Y",$events[$i]->getStartDate())." at ".$events[$i]->getVenue()->getName()."</a></li>";
													}
												}

												else echo "<li>No upcoming events found!</li>";
											?>
										</ul>

										<?php if(sizeof($events)>0) echo "<a href='events.php?artist=".$artist->getURI()."'>See more</a>"; ?>
									</section>

							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- Footer -->
			<div id="footer-wrapper">
				<footer id="footer" class="5grid-layout">
					<div class="row">
						<div class="8u">
						
							<!-- Links -->
								<section>
									<h2>Links to Important Stuff</h2>
									<div class="5grid">
										<div class="row">
											<div class="3u">
												<ul class="link-list last-child">
													<li><a href="#">Neque amet dapibus</a></li>
													<li><a href="#">Sed mattis quis rutrum</a></li>
													<li><a href="#">Accumsan suspendisse</a></li>
													<li><a href="#">Eu varius vitae magna</a></li>
												</ul>
											</div>
											<div class="3u">
												<ul class="link-list last-child">
													<li><a href="#">Neque amet dapibus</a></li>
													<li><a href="#">Sed mattis quis rutrum</a></li>
													<li><a href="#">Accumsan suspendisse</a></li>
													<li><a href="#">Eu varius vitae magna</a></li>
												</ul>
											</div>
											<div class="3u">
												<ul class="link-list last-child">
													<li><a href="#">Neque amet dapibus</a></li>
													<li><a href="#">Sed mattis quis rutrum</a></li>
													<li><a href="#">Accumsan suspendisse</a></li>
													<li><a href="#">Eu varius vitae magna</a></li>
												</ul>
											</div>
											<div class="3u">
												<ul class="link-list last-child">
													<li><a href="#">Neque amet dapibus</a></li>
													<li><a href="#">Sed mattis quis rutrum</a></li>
													<li><a href="#">Accumsan suspendisse</a></li>
													<li><a href="#">Eu varius vitae magna</a></li>
												</ul>
											</div>
										</div>
									</div>
								</section>

						</div>
						<div class="4u">
							
							<!-- Blurb -->
								<section>
									<h2>An Informative Text Blurb</h2>
									<p>
										Duis neque nisi, dapibus sed mattis quis, rutrum accumsan sed. Suspendisse eu 
										varius nibh. Suspendisse vitae magna eget odio amet mollis. Duis neque nisi, 
										dapibus sed mattis quis, sed rutrum accumsan sed. Suspendisse eu varius nibh 
										lorem ipsum amet dolor sit amet lorem ipsum consequat gravida justo mollis.
									</p>
								</section>
						
						</div>
					</div>
				</footer>
			</div>

		<!-- Copyright -->
			<div id="copyright">
				&copy; djpatty. All rights reserved.
			</div>

	</body>
</html>