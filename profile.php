<?php

require_once 'functions.php';

$follows = null ;

if (!Input::exists('get') and $is_on ){

	$recent_videos = makeProfileVideoGreed($getIdentity['id']);
	
	echo $Theme->render('profile.html', array(
		'follows' => $follows,
		'user' => $getIdentity,
		'profile' => $getIdentity,
		'recentVideos' =>  $recent_videos 
	));

} elseif (Input::get('name') and $is_on	) {

	$cleaner = new Sanitize();
	$pf = new User($cleaner->clean(Input::get('name')));
	$profileowner = $pf->data();
	if ($profileowner['avatar'] == null) {
		$profileowner['avatar'] = $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/default.png';
	}
	$useroptions = $_db->where('user_id', $profileowner['id'])->getOne('mtb_users_options');
	$profile = array_merge($profileowner, $useroptions);
	
	$recent_videos = makeProfileVideoGreed($pf->data()['id']);
	
	$follows = $mytube->subscribed($getIdentity['id'], $pf->data()['id']);
	
	$follows = $follows['status'];
	

	echo $Theme->render('profile.html', array(
		'user' => $getIdentity,
		'follows' => $follows,
		'profile' => $profile,
		'recentVideos' =>  $recent_videos 
	));

} else{
	$cleaner = new Sanitize();
	$pf = new User($cleaner->clean(Input::get('name')));
	$profileowner = $pf->data();
	if ($profileowner['avatar'] == null) {
		$profileowner['avatar'] = $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/default.png';
	}
	$useroptions = $_db->where('user_id', $profileowner['id'])->getOne('mtb_users_options');
	$profile = array_merge($profileowner, $useroptions);

	$recent_videos = makeProfileVideoGreed($profile['id']);
	echo $Theme->render('profile.html', array(
		'user' => $getIdentity,
		'follows' => $follows,
		'profile' => $profile,
		'recentVideos' =>  $recent_videos 
	));
}