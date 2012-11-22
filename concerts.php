<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Title Goes Here</title>
		<link rel="stylesheet/less" type="text/css" href="less/bootstrap.less" />

	</head>
	<body>
		<?php
			require_once('metatune/lib/config.php');

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
						require "lastfm_api/lastfm.api.php";
						echo "test";
						CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

						$events = Artist::getEvents("blof");
						if(!empty($events)){
							foreach ($events as $key => $event) {
								?>
								<tr>
									<td><?php echo $event->getTitle(); ?></td>
									<td><?php echo implode(",",$event->getArtists()); ?></td>
									<td><?php echo date(DATE_RSS,$event->getStartDate()); ?></td>
									<td><?php echo $event->getVenue()->getName(); ?></td>
								</tr>
							<?php
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