<!DOCTYPE HTML>
<!--
	Halcyonic 2.0 by HTML5 Up!
	html5up.net | @nodethirtythree
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<?php
	require_once('../metatune/lib/config.php');

	$spotify = MetaTune::getInstance();
	if(isset($_REQUEST['q'])){
		$query = $_REQUEST['q'];
	}
	else {
	}
?>

<html>
	<head>
		<title>Search results for '<?php echo $query ?>'</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<noscript><link rel="stylesheet" href="css/5grid/core.css" /><link rel="stylesheet" href="css/5grid/core-desktop.css" /><link rel="stylesheet" href="css/5grid/core-1200px.css" /><link rel="stylesheet" href="css/5grid/core-noscript.css" /><link rel="stylesheet" href="css/style.css" /><link rel="stylesheet" href="css/style-desktop.css" /></noscript>
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
								<h1 class="mobileUI-site-name"><a href="#">djpatty</a></h1>
							
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
							<div class="12u">
							
								<!-- Main Content -->
									<section>
										<h2>Artists</h2>
										<table class="table">
											<?php
												$artists = $spotify->searchArtist($query);

													if(count($artists)>0){
														foreach($artists as $artist){
															echo "<tr>";
															echo "<td><a href='" . $artist->getURL() . "'>" . $artist->getName() . "</a></td>";
															echo "</tr>";
														}
													}
													else echo "<td colspan='4'>No search results</td>";
											?>
										</table>

										<h2>Tracks</h2>
										<?php
											$tracks = $spotify->searchTrack($query);

											if(count($tracks)>0){
										?>
										<table class="table">
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
															echo "<td><a href='" . ((is_array($artist)) ? $artist[0]->getURL() : $artist->getURL()) . "'>" . $track->getArtistAsString() . "</a></td>";
															echo "<td><a href='" . $track->getAlbum()->getURL() . "'>" . $track->getAlbum() . "</a></td>";
															echo "<td>" . $track->getLengthInMinutesAsString() . "</td>";
															echo "</tr>";
														}
													}
													else echo "<td colspan='4'>No search results</td>";
													
												?>
											</tbody>
										</table>

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