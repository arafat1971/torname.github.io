<?php

require_once 'functions.php';

$recent_videos = makeRecentVideosCards(15);
$most_liked = makeMostLikedVideosCards(5);
$most_viewed = makeMostViewedVideosCards(5);
$load_more = makeLoadMoreBtn($recent_videos['offset'], 5);
$users = $mytube->getUsers(14);

echo $Theme->render('all.html', array(
	'load_more'	=>	$load_more,
	'user' => $getIdentity,
	'users'	=>	$users,
	'recentVideos' =>  $recent_videos['html'],
	'mostliked'	=>	$most_liked['html'],
	'mostviewed'	=>	$most_viewed['html'] 
));