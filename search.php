<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Title Goes Here</title>
		<link rel="stylesheet/less" type="text/css" href="less/djpatty.less" />

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
			<h1>Artists</h1>
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

			<h1>Tracks</h1>
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
						$tracks = $spotify->searchTrack($query);

						if(count($tracks)>0){
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
		</div>
		<!-- INCLUDE SCRIPTS -->
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.js" type="text/javascript"></script>
		<script src="js/less.js" type="text/javascript"></script>
	</body>
</html>