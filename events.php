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
	if(isset($_REQUEST['artist'])){
		$uri = $_REQUEST['artist'];
		$artist = $spotify->lookupArtist($uri,true);
		$lastfmArtist = Artist::getInfo($artist);
		$artistpop = $spotify->searchArtist($artist->getName())[0];
	}
	else {
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
																if($event->getDescription()!=null){
																	echo "<td colspan='4'>"."<img class='descriptionimage' src='".$image."'>"."<h3>Description</h3>".$event->getDescription()."</td>";
																}
																else{
																	echo "<td colspan='4'>"."<img class='descriptionimage' src='".$image."'>"."No description available</td>";
																}
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
											<h2>Related Events</h2>
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
											<h2>Artist Info</h2>
										</header>
										<ul class="link-list">
											<li><label class="eventinfo">Name:</label> <?php echo $artist->getName() ?></li>
											<li><label class="eventinfo">Spotify score:</label> <?php echo $artistpop->getPopularityAsPercent() ?>%</li>
											<?php
												$members = $lastfmArtist->getBandmembers();
												if($members != null){
													echo "<li><label class='eventinfo'>Bandmembers: </label>".implode(', ',$members).".</li>";
												}
											?>
											<li><a href="#">Dis parturient montes</a></li>
											<li><a href="#">Nascetur ridiculus</a></li>
										</ul>
										<?php echo "<a href='artist.php?uri=".$artist->getURI()."'>See more</a>"; ?>
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
				&copy; Untitled. All rights reserved. | Design: <a href="http://html5up.net">HTML5 Up!</a> | Images: <a href="http://fotogrph.com">Fotogrph</a>
			</div>

	</body>
</html>