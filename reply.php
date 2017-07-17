<?php

require_once 'functions.php';

$videoPlayer = false;

if (Input::exists('get')) {
	
	if (isset($_GET['d']) and isset($_GET['c']) and isset($_GET['r'])) {
		
		$play = $mytube->getVideo(Input::get('d'));
		
		if ($play) {
			$videoPlayer = makeVideoPlayer($play, 'watch');
		} 
		$sidebarVideos = makeSidebarVideoCardsByAuthorId($play['vid_author_id'], 5);
		
		$reply = AddOneCommentToVideo($_GET['r']);

		if (Input::get('c') == Input::get('r')) {
			$comment = AddOneCommentToVideo($reply['data'][0]['parent_id']);
		}else{
			$comment = AddOneCommentToVideo($_GET['c']);
		}
		if (sizeof($reply['data']) == 0) {
			Redirect::to('index.php');
		}
		$replyform = makeVideoCommentForm($play['id'],true,$_GET['r'],$reply['data'][0]['author_name'],$reply['data'][0]['author_avatar'], base64_encode($reply['data'][0]['comment']) , 2 );
		$replies = AddRepliesToVideo($_GET['r'], $_GET['r'], null, 12);
		$ad_codes = $mytube->getAdCodes($play['vid_author_id']);
		
		echo $Theme->render('reply.html', array(
			'user' => $getIdentity,
			'videoPlayer' => $videoPlayer,
			'video'	=>	$play,
			'replyform' => $replyform,
			'replies' => $replies,
			'comment'	=>	$comment['html'],
			'reply'	=>	$reply['html'],
			'ad_codes'	=>	$ad_codes 
		));

		$mytube->addView($_GET['d']);

	} else if ( isset($_GET['d']) and isset($_GET['c']) ) {
		$play = $mytube->getVideo(Input::get('d'));
		if ($play) {
			$videoPlayer = makeVideoPlayer($play, 'watch');
		} 
		$sidebarVideos = makeSidebarVideoCardsByAuthorId($play['vid_author_id'], 5);
		$comment = AddOneCommentToVideo($_GET['c']);
		if (sizeof($comment['data']) == 0) {
			Redirect::to('index.php');
		}
		$replyform = makeVideoCommentForm($play['id'],true,$_GET['c'],$comment['data'][0]['author_name'],$comment['data'][0]['author_avatar'], base64_encode($comment['data'][0]['comment']) , 1 );
		$replies = AddRepliesToVideo($_GET['c']);
		$ad_codes = $mytube->getAdCodes($play['vid_author_id']);
		echo $Theme->render('reply.html', array(
			'user' => $getIdentity,
			'videoPlayer' => $videoPlayer,
			'video'	=>	$play,
			'replyform' => $replyform,
			'replies' => $replies,
			'comment'	=>	$comment['html'],
			'ad_codes'	=>	$ad_codes 
		));

		$mytube->addView($_GET['d']);

	}else{
		Redirect::to('index.php');
	}
	
} else {
	
	Redirect::to('index.php');
}



