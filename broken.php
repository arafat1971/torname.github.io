<?php

require_once 'functions.php';

$follows = null ;

if ($getIdentity == false) {
	Redirect::to('index.php');
}


	$recent_videos = makeInativeVideoGreed($getIdentity['id']);
	
	echo $Theme->render('broken.html', array(
		'user' => $getIdentity,
		'recentVideos' =>  $recent_videos 
	));
