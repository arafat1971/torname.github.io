<?php

require_once 'functions.php';


if ($getIdentity) {



	$videos = makeTimelineVideos($getIdentity['id']);
	$load_more = makeLoadMoreBtn($videos['offset'], 8);

	echo $Theme->render('timeline.html', array(
		'user' => $getIdentity,
		'load_more'	=>	$load_more,
		'videos' => $videos['html']
	));

} else  {
	Redirect::to('profile.php?name='.$_GET['name']);
}
