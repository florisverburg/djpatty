<?php

	require_once('metatune/lib/config.php');
	require_once('lastfm_api/lastfm.api.php');

	$LAST_FM_API_KEY = '764d5b2b6e44a878abcb9dba6d77d33f';
	CallerFactory::getDefaultCaller()->setApiKey($LAST_FM_API_KEY);

	$spotify = MetaTune::getInstance();
	if(isset($_GET['artisturi']) && isset($_GET['albumuri']) && isset($_GET['artist']) && isset($_GET['album'])){
		$artisturi = $_GET['artisturi'];
		$albumuri = $_GET['albumuri'];
		$artist = $_GET['artist'];
		$album = $_GET['album'];

		$artist = $spotify->lookupArtist($artisturi,true);
		$lastfmArtist = Artist::getInfo($artist);

		$album = $spotify->lookupAlbum($albumuri,true);
		$lastfmAlbum = Album::getInfo($artist,$album);

		$tracks = $album->getTracks();

		$echo = "<h3>".$album->getName()." (".$album->getRelease().")</h3><div class='albumArt' id='".$album->getURI()."'><img src='".$lastfmAlbum->getImage(Media::IMAGE_LARGE)."' /></div><div class='tracklist'><table><thead><tr><th class='tracknumber'></th><th>Title</th><th class='duration'>Duration</th></tr></thead><tbody>";
		$i = 1;
		foreach($tracks as $track){
			$echo .= "<tr class='track' data-original-title='test' data-album='".$track->getAlbum()->getURI()."' id='".$track->getURI()."''>";
			$echo .= "<td>".$i."</td>";
			$echo .= "<td>".$track->getTitle()."</td>";
			$echo .= "<td>".$track->getLengthInMinutesAsString()."</td>";
			$echo .= "</tr>";
			$i++;
		}
		$echo .= "</tbody></table></div>";

		echo $albumuri."@".$echo;
	}
	else echo "<p>Album could not be loaded</p>";
?>