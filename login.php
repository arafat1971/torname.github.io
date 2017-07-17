<?php
require_once 'functions.php';

($getIdentity)? Redirect::to('index.php') : '' ;

$vids = explode(',', $mtbOptions['welcomevids']);
$max = sizeof($vids) - 1;
$index = rand(0, $max);
$play = $mytube->getVideo($vids[$index]);

$recent_videos = makeRecentVideosCards(5);

$card = mountCard($play['vid_author_id'], $lang['Video']." ".$lang['by']);

$Errors = false;

if (Input::exists()) {

	if (Token::check(Input::get('token'))) {
		

		$validate = new Validate();
		$validation = $validate->check($_POST,array(
		'username' => array(
			'required' => true,
			'exists'	=>  array( 
				'table' => 'mtb_users' ,
				'field'	=> 'username' 
				),
			'alphanumeric' => true,
			'min' => 5
			),
		'password' => array('required' => true)
		));

	
		
		if($validation->passed()){
			
			$user = new User();
			$remember = (Input::get('remember') == 'on' )? true : false;

			$login = $user->login(Input::get('username'),Input::get('password'), $remember);

			if ($login) {
				
				Redirect::to('index.php');

			} else{
				$Errors = array();
				array_push($Errors, array( 'field'		=> 'password', 
											   'message'	=>  $lang['Username and Password don\'t match'] 
				));

			}	

		} else {

			$x = 0 ;
			$Errors = array();
			foreach ($validation->errors() as $errors ) {

					array_push($Errors, $errors);

			}

		}
	} else {
		$Errors = array();
		array_push($Errors, array('field' => 'username', 'message' => 'invalid token'));
	}

}


echo $Theme->render('login.html', array(
	'video'	=>$play,
	'errors'=> $Errors,
	'card'	=> $card,
	'username'	=> Input::get('username'),
	'password' => Input::get('password'),
	'recent'	=>	$recent_videos
));
