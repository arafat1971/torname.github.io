<?php

require_once 'functions.php';
$play = false;

$play = $mytube->getVideo();
$videoPlayer = false;

if ($play and $play['is_active'] == 0) {
	$play = $mytube->getNextVideo($play['id']);
}

if ($play) {
	$videoPlayer = makeVideoPlayer($play, 'index');
} 
$mytube->addView($play['id']);
$recent_videos = makeRecentVideosCards();

$author_card = mountCard($play['vid_author_id'], "");


echo $Theme->render('last.html', array(
	'user' => $getIdentity,
	'video' => $play,
	'authorCard' => $author_card,
	'videoPlayer' => $videoPlayer,
	'recentVideos' =>  $recent_videos['html']
));
