<?php

function makeLoadMoreBtn($offset, $limit = 8){
	global $lang,$Theme;
	return $Theme->render('loadmore.html', array(
			'lang'	=>	$lang,
			'offset'	=>	$offset,
			'limit'	=>	$limit
		));

}

function makeVideoPlayer(array $videodata , $_route ){
 	
 	$follows = null ;
 	$liked = null ;
 	
 	global $lang,$Theme,$getIdentity, $mytube, $mtbOptions;

 	if ($getIdentity) {
			
			$owner = new User($videodata['vid_author_id']);

			$follows = $mytube->subscribed($getIdentity['id'], $owner->data()['id']);

			$liked = $mytube->liked($getIdentity['id'], 'video', $videodata['id']);

		} else {
			$follows = $mytube->subscribed(false, $videodata['vid_author_id']);
		}
 	if ($videodata['vid_author_avatar'] == null ) {
			$videodata['vid_author_avatar'] = $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/default.png';
		}
	return $Theme->render('videoPlayer.html', array(
	'lang' => $lang,
	'video' => $videodata,
	'follows'	=>	$follows,
	'_route' => $_route,
	'user'	=>	$getIdentity,
	'liked'	=>	$liked
	));
}

function mountCard($id, $title){

	global $lang,$Theme,$paths;
	
	$user = new User($id);

	return $Theme->render('authorcard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'title' => $title,
			'card' => $user->data(),
		));


}

function suggestMostActiveUsers(){
	global $getIdentity, $mytube, $Theme;

	$users = $mytube->suggestionsByMostActiveUser($getIdentity['id']);

	if ($users != false) {
		return $Theme->render('suggestions.html', array(
			'users' => $users,
		));
	}

	return false;
	
}

function makeNextVideo($id){
	global $mytube, $lang,$Theme,$paths, $getIdentity;

	$video = $mytube->getNextVideo($id);

	return array(
		'data'	=>	$video,
		'html'	=> $Theme->render('next.html', array(
						'paths' => $paths,
						'lang' => $lang,
						'video' => $video,
						'user' => $getIdentity
					))
		);
}

function makeRecentVideosCards($limit = 8, $offset = 0, $ret_json = false){
	
	global $mytube, $lang,$Theme,$paths, $getIdentity;


	$videos = $mytube->getRecentVideos(	$limit , $offset);

	if($videos == false){
		return ' ';
	} 

	$plain_data = array();

	foreach ($videos as $video) {

		if ($video['source_vid_id'] == '') return ;
		if ($video['source'] != 'local')
			//$video['title'] = substr(json_decode($video['title']),0,40).' ...';
		$video['views'] = number_format($video['views']);

		if ( $getIdentity and $getIdentity['id'] == $video['vid_author_id']) {
			$video['vid_admin'] = true;
		}

		/*$data = array ("id" => $video['id'], "data" => $video);
		array_push($plain_data, $data);*/

		array_push($plain_data, $video);

	}

	$html = $Theme->render('videocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'videos' => $plain_data,
		));


	
		$collection_size = sizeof($videos) - 1 ;
		$offset = $plain_data[$collection_size]['id'];


	$return = array(
		'html'	=>	$html,
		'data'	=>	$plain_data,
		'offset'	=> $offset
	);

	if ($ret_json) {
		return json_encode($return);
	}

	return $return;
}
function makeMostLikedVideosCards($limit = 5, $offset = 0, $ret_json = false){
	
	global $mytube, $lang,$Theme,$paths, $getIdentity;


	$videos = $mytube->getMostLikedVideos($limit , $offset);

	if($videos == false){
		return ' ';
	} 

	$plain_data = array();

	foreach ($videos as $video) {

		if ($video['source_vid_id'] == '') return ;
		if ($video['source'] != 'local')
			//$video['title'] = substr(json_decode($video['title']),0,40).' ...';
		$video['views'] = number_format($video['views']);

		if ( $getIdentity and $getIdentity['id'] == $video['vid_author_id']) {
			$video['vid_admin'] = true;
		}

		/*$data = array ("id" => $video['id'], "data" => $video);
		array_push($plain_data, $data);*/

		array_push($plain_data, $video);

	}

	$html = $Theme->render('videocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'videos' => $plain_data,
		));


	
		$collection_size = sizeof($videos) - 1 ;
		$offset = $plain_data[$collection_size]['id'];


	$return = array(
		'html'	=>	$html,
		'data'	=>	$plain_data,
		'offset'	=> $offset
	);

	if ($ret_json) {
		return json_encode($return);
	}

	return $return;
}
function makeMostViewedVideosCards($limit = 5, $offset = 0, $ret_json = false){
	
	global $mytube, $lang,$Theme,$paths, $getIdentity;


	$videos = $mytube->getMostViewedVideos($limit , $offset);

	if($videos == false){
		return ' ';
	} 

	$plain_data = array();

	foreach ($videos as $video) {

		if ($video['source_vid_id'] == '') return ;
		if ($video['source'] != 'local')
			//$video['title'] = substr(json_decode($video['title']),0,40).' ...';
		$video['views'] = number_format($video['views']);

		if ( $getIdentity and $getIdentity['id'] == $video['vid_author_id']) {
			$video['vid_admin'] = true;
		}

		/*$data = array ("id" => $video['id'], "data" => $video);
		array_push($plain_data, $data);*/

		array_push($plain_data, $video);

	}

	$html = $Theme->render('videocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'videos' => $plain_data,
		));


	
		$collection_size = sizeof($videos) - 1 ;
		$offset = $plain_data[$collection_size]['id'];


	$return = array(
		'html'	=>	$html,
		'data'	=>	$plain_data,
		'offset'	=> $offset
	);

	if ($ret_json) {
		return json_encode($return);
	}

	return $return;
}


function makeTimelineVideos($user_id, $limit = 8, $offset = 0, $ret_json = false){

	global $mytube, $lang,$Theme,$paths;

	$videos = $mytube->getUserVideoFeeds($user_id, $limit, $offset);
	$plain_data = array();
	$html ='';

	foreach ($videos as $video) {
		$video['listing_date'] = $mytube->time_elapsed_string($video['listing_date'], $lang);
		switch ($video['source']) {
			case 'youtube':
				$card = 'youtubecard';
				$video['vid_thumbnail'] = str_replace('/mq', '/sd', $video['vid_thumbnail']);	
			break;
			
			default:
				$card = 'youtubecard';
			break;
		}

		$html.= $Theme->render($card.'.html',array(
			'paths' => $paths,
			'lang' => $lang,
			'video' => $video
			));	

		array_push($plain_data, $video);
	}


	if(sizeof($videos) > 0 ){ 
		$collection_size = sizeof($videos) - 1 ;
		$offset = $plain_data[$collection_size]['video_id'];
	} else {
		$offset = false;
	}

	$return = array(
		'html'	=>	$html,
		'data'	=>	$plain_data,
		'offset'	=> $offset
	);

	if ($ret_json) {
		return json_encode($return);
	}

	return $return;

}

function makeActivityFeed($user_id, $limit = 8, $offset = 0, $ret_json = false ){
	global $mytube, $lang,$Theme,$paths,$mtbOptions;

	$html = '' ;
	$plain_data = array();

	$activity = $mytube->getActivityFeeds($user_id,	$limit, $offset);
	
	if (count($activity) > 0) {
		
		foreach ($activity as $act ) {
		
			$json = json_decode($act['comment_json_data'],true);
			$act['created_at'] = $mytube->time_elapsed_string($act['created_at'], $lang);
			$act['type'] = $json['type'];
			$act['active'] = $json['active'];
			$act['description'] = substr($act['description'],0,340).'<strong> . . .</strong>';
			$act['description'] = $mytube->makelinks($act['description']);
			if ($act['type'] == 'youtube-post') {
				$act['vid_thumbnail'] = str_replace('/mq', '/sd', $act['vid_thumbnail']);
			}
			if ($act['author_avatar'] == NULL) {
				$act['author_avatar'] = $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/default.png';
			}
			$html.= $Theme->render('activitycard.html',array(
				'paths' => $paths,
				'lang' => $lang,
				'activity' => $act,
				));
			array_push($plain_data, $act);

		}

		$return = array(
			'html'	=>	$html,
			'data'	=>	$plain_data,
			'offset'	=> end($plain_data)['activity_id'] //$offset
		);

		if ($ret_json) {
			return json_encode($return);
		}

		return $return;

	}
	
	return false;
	/*if(sizeof($activity) > 0 ){ 
		$collection_size = sizeof($activity) - 1 ;
		$offset = $plain_data[$collection_size]['activity_id'];
	} else {
		$offset = false;
	}*/
}

function makePublicActivityFeed( $limit = 8, $offset = 0, $ret_json = false ){
	global $mytube, $lang,$Theme,$paths,$mtbOptions;

	$html = '' ;
	$plain_data = array();

	$activity = $mytube->getPublicActivityFeeds($limit, $offset);
	
	if (count($activity) > 0) {
		
		foreach ($activity as $act ) {
		
			$json = json_decode($act['comment_json_data'],true);
			$act['created_at'] = $mytube->time_elapsed_string($act['created_at'], $lang);
			$act['type'] = $json['type'];
			$act['active'] = $json['active'];
			$act['description'] = substr($act['description'],0,340).'<strong> . . .</strong>';
			$act['description'] = $mytube->makelinks($act['description']);
			if ($act['type'] == 'youtube-post') {
				$act['vid_thumbnail'] = str_replace('/mq', '/sd', $act['vid_thumbnail']);
			}
			if ($act['author_avatar'] == NULL) {
				$act['author_avatar'] = $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/default.png';
			}
			$html.= $Theme->render('activitycard.html',array(
				'paths' => $paths,
				'lang' => $lang,
				'activity' => $act,
				));
			array_push($plain_data, $act);

		}

		$return = array(
			'html'	=>	$html,
			'data'	=>	$plain_data,
			'offset'	=> end($plain_data)['activity_id'] //$offset
		);

		if ($ret_json) {
			return json_encode($return);
		}

		return $return;

	}
	
	return false;
	/*if(sizeof($activity) > 0 ){ 
		$collection_size = sizeof($activity) - 1 ;
		$offset = $plain_data[$collection_size]['activity_id'];
	} else {
		$offset = false;
	}*/
}

function makeSidebarVideoCardsByAuthorId($id , $limit){

	global $mytube, $lang,$Theme,$paths;
	$videos = $mytube->getVideosByAuthorId($id, $limit);

	$vcards = array();

	foreach ($videos as $video) {

		if ($video['source_vid_id'] == '') return ;
		//$video['title'] = substr(json_decode($video['title']),0,40).' ...';
		$video['views'] = number_format($video['views']);

		$data = array ("id" => $video['id'], "data" => $video);

		array_push($vcards, $data);
	}


	return $Theme->render('sidebarvideocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'videos' => $vcards,
		));
}

function makeSearchResultVideos($q ){

	global $mytube, $lang,$Theme,$paths;

	$result = $mytube->searchVideosByTitle($q);

	$vcards = array();
	$tags = '';
	foreach ($result['tags'] as $tag) {
		$tags .= $tag.' ';
	}


	foreach ($result['videos'] as $video) {

		//if ($video['source_vid_id'] == '') return ;
		$video['title'] = $mytube->highlight($video['title'], $tags );
		$video['views'] = number_format($video['views']);

		$vid = array ("id" => $video['id'], "data" => $video);

		array_push($vcards, $vid);
	}

	$meta = array(
		'tags' => $result['tags'],
		'html' => $Theme->render('sidebarvideocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'big'	=> true,
			'videos' => $vcards,
		))
	);


	return $meta;
}

function makeProfileVideoGreed($profileid){

	global $mytube, $lang,$Theme,$paths,$getIdentity;
	$videos = $mytube->getVideosByAuthorId($profileid);
	$vcards = array();

	foreach ($videos as $video) {

		if ($video['source_vid_id'] == '') return ;

		//$video['title'] = substr(json_decode($video['title']),0,40).' ...';
		$video['views'] = number_format($video['views']);
		if ( $getIdentity and $getIdentity['id'] == $video['vid_author_id']) {
			$video['vid_admin'] = true;
		}

		array_push($vcards, $video);
	}


	return $Theme->render('videocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'videos' => $vcards,
		));
}
function makeInativeVideoGreed($profileid){

	global $mytube, $lang,$Theme,$paths,$getIdentity,$mtbOptions;
	$videos = $mytube->getInactiveVideosByAuthorId($profileid);
	$vcards = array();

	foreach ($videos as $video) {

		if ($video['source_vid_id'] == '') return ;

		//$video['title'] = substr(json_decode($video['title']),0,40).' ...';
		$video['views'] = number_format($video['views']);
		if ( $getIdentity and $getIdentity['id'] == $video['vid_author_id']) {
			$video['vid_admin'] = true;
		}

		if ($video['vid_thumbnail'] == NULL) {
			$video['vid_thumbnail'] =  $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/thumbnails/default.jpg';
		}
		array_push($vcards, $video);
	}


	return $Theme->render('videocard.html', array(
			'paths' => $paths,
			'lang' => $lang,
			'videos' => $vcards,
		));
}

function makeVideoCommentForm($resource, $type = false, $parent = 0, $name = false, $avatar = false, $comment = false ,$level = 0){
	
	global $mytube,$lang,$paths,$getIdentity;

	$form = $mytube->loadCommentForm($resource, $lang, $paths, $getIdentity, $type, $parent, $name, $avatar, $comment ,$level);

	return $form;

}

function AddCommentsToVideo($resource_id){

	global $mytube,$lang,$Theme,$paths,$getIdentity;

	$comments_array = $mytube->getVideoComments($resource_id, 6);

	return  $Theme->render('comment.html', array(
				'paths' => $paths,
				'lang' => $lang,
				'user' => $getIdentity,
				'commentdata' => $comments_array,
	));

}

function AddOneCommentToVideo($comment_id){

	global $mytube,$lang,$Theme,$paths,$getIdentity;

	$comment = $mytube->getVideoCommentById($comment_id);

	$html = $Theme->render('comment.html', array(
				'paths' => $paths,
				'lang' => $lang,
				'user' => $getIdentity,
				'single'	=>	true,
				'commentdata' => $comment,
	));

	return array(
		'html' => $html,
		'data' => $comment
		);
}

function AddRepliesToVideo($comment_id, $duplicate = false, array $friends = null, $limit = 6 ){
	global $mytube,$lang,$Theme,$paths,$getIdentity;

	$comments_array = $mytube->getCommentReplies($comment_id, $duplicate, null ,$limit);

	return  $Theme->render('comment.html', array(
				'paths' => $paths,
				'lang' => $lang,
				'user' => $getIdentity,
				'commentdata' => $comments_array,
	));

}
