<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Title Goes Here</title>
		<link rel="stylesheet/less" type="text/css" href="less/bootstrap.less" />

	</head>
	<body>
		<?php
			require 'lastfm_api/lastfm.api.php';
			require 'metatune/lib/config.php';

			$spotify = MetaTune::getInstance();
			if(isset($_REQUEST['q'])){
				$query = $_REQUEST['q'];
			}
			else {
			}
		?>

		<div class="content">
			<table class="table">
				<thead>
					<tr>
						<th>Title</th>
						<th>Artists</th>
						<th>Date</th>
						<th>Venue</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
						CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);
						
						$events = Artist::getEvents($query);
						if(!empty($events)){
							foreach ($events as $key => $event) {
								echo "<tr>";
								echo "<td>" . $event->getTitle() . "</td>";
								echo "<td>" . $event->getArtists()['headliner'] . "</td>";
								echo "<td>" . date(DATE_RSS,$event->getStartDate()) . "</td>";
								echo "<td>" . $event->getVenue()->getName() . "</td>";
								echo "</tr>";
							}
						}
					?>
				</tbody>
			</table>
		</div>
		<!-- INCLUDE SCRIPTS -->
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.js" type="text/javascript"></script>
		<script src="js/less.js" type="text/javascript"></script>
	</body>
</html>