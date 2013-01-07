<!DOCTYPE HTML>
<!--
	Halcyonic 2.0 by HTML5 Up!
	html5up.net | @nodethirtythree
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<?php 

if(isset($_REQUEST['status'])){
	$status = $_REQUEST['status'];
	if($status){
?>
<script>alert('You have registered succesfully! Now redirecting to login page...'); window.location = "login.php";</script>
<?php
	}
	else {
?>
<script>alert('Your registration failed. Perhaps the email is already in use?');</script>
<?php 
	}
}
?>

<html>
	<head>
		<title>Halcyonic: A Responsive HTML5 Site Template by HTML5 Up! (One Column)</title>
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
							<div class="12u">
							
								<!-- Main Content -->
								<div class="4u">
									
								</div>
								<div class="4u">
									<section>
										<div class="content">
											<div class="form">
												<center>
													<form method="post" action="adduser.php">
														<h2>Register</h2>
														<input id="input-username" name="username" type="text" placeholder="Email">
														<br /><input id="input-password" name="password" type="password" placeholder="Password">
														<br /><input id="input-first-name" name="first-name" type="text" placeholder="First Name (optional)">
														<br /><input id="input-last-name" name="last-name" type="text" placeholder="Last Name (optional)">
														<br /><button type="submit" class="button-small">Register</button>
													</form>
												</center>
											</div>
										</div>
									</section>
								</div>
								<div class="4u">
									
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- Copyright -->
			<div id="copyright">
				&copy; Untitled. All rights reserved. | Design: <a href="http://html5up.net">HTML5 Up!</a> | Images: <a href="http://fotogrph.com">Fotogrph</a>
			</div>

	</body>
</html>