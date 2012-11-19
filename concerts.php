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
			require __DIR__ . "/lastfm_api/lastfm.api.php";
			CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

			$events = Artist::getEvents("Blof");
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