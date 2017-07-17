<?php

require_once 'functions.php';




	$activity = makePublicActivityFeed(8);

	$load_more = makeLoadMoreBtn($activity['offset'], 8);

	$suggestions = suggestMostActiveUsers();
	
	echo $Theme->render('publicactivity.html', array(
		'user' => $getIdentity,
		'activity' =>  $activity['html'],
		'load_more'	=>	$load_more,
		'suggestions'	=>	$suggestions
	));


