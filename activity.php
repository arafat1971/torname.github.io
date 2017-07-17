<?php

require_once 'functions.php';


if ($getIdentity) {


	if(Input::exists()){

		if (Token::check(Input::get('token'))) {
			$validate = new Validate();

			$validation = $validate->check($_POST,array(
			'p' => array(
				'required' => true,
				'min'	=>	2,
				'max'	=> 500
				),
			));

			if($validation->passed()){
				$data = array(
					'author_id' => $getIdentity['id'],
					'author_name' =>	$getIdentity['username'],
					'author_avatar'=> $getIdentity['avatar'],
					'parent_id' => 0,
					'level' => 3,
					'parent_author_name' => NULL,
					'parent_author_avatar' => NULL,
					'comment' => Input::get('p'),
					'parent_comment' => NULL,
					'likes' => 0,
					'comment_json_data' => '{"init_set":1,"type":null,"active":true}',
					'created_at' => date('Y:m:d H:i:s'),
					'updated_at' => date('Y:m:d H:i:s')
				);

				
				if($mytube->addcomment($data)){
					
					Redirect::to('activity.php');
				}

			}
			else{
				// validation errors
			}
		} 
		else {
			// Invalid token 
		}
	} 
	else {
		// there is no input
	}



	$activity = makeActivityFeed($getIdentity['id'], 8);

	$load_more = makeLoadMoreBtn($activity['offset'], 8);

	$suggestions = suggestMostActiveUsers();
	
	echo $Theme->render('activity.html', array(
		'user' => $getIdentity,
		'activity' =>  $activity['html'],
		'load_more'	=>	$load_more,
		'suggestions'	=>	$suggestions
	));

} else  {
	Redirect::to('index.php');
}
