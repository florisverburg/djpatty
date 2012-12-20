<!doctype html>

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

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=9,chrome=1">
		<meta name="author" content="djpatty">
		<meta name="viewport"
			content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">

		<title>Login to djpatty</title>


	</head>
	<body>
		<div class="content">
			<div class="form">
				<div class="">
					<form method="post" action="adduser.php">
						<legend>Register</legend>
						<input id="input-username" name="username" type="text" placeholder="Email">
						<input id="input-password" name="password" type="password" placeholder="Password">
						<input id="input-first-name" name="first-name" type="text" placeholder="First Name (optional)">
						<input id="input-last-name" name="last-name" type="text" placeholder="Last Name (optional)">
						<button type="submit">Register</button>
					</form>
				</div>
			</div>
		</div>

	</body>
</html>