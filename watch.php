<?php

require_once 'functions.php';



$videoPlayer = $author_card = false;

if (Input::exists('get')) {
	
	if (isset($_GET['d'])) {
		
		$play = $mytube->getVideo(Input::get('d'));
		if ($play) {
			$videoPlayer = makeVideoPlayer($play, 'watch');
		} 

		$next = makeNextVideo(Input::get('d'));

		$sidebarVideos = makeSidebarVideoCardsByAuthorId($play['vid_author_id'], 5);
				
		$author_card = mountCard($next['data']['vid_author_id'], $lang['Next up']);
			
		$commentform = makeVideoCommentForm($play['id'], false, 0, $play['vid_author_name'], $play['vid_author_avatar']);

		$comments = AddCommentsToVideo($play['id']);

		$ad_codes = $mytube->getAdCodes($play['vid_author_id']);

		$mytube->addView($_GET['d']);

	} else {

	}
	
} else {

}

echo $Theme->render('watch.html', array(
	'user' => $getIdentity,
	'authorCard' => $author_card,
	'videoPlayer' => $videoPlayer,
	'video' => $play,
	'comments' => $comments,
	'commentform' => $commentform,
	'sidebarVideos' =>  $sidebarVideos,
	'next'	=> $next['html'],
	'ad_codes'	=>	$ad_codes 
));

