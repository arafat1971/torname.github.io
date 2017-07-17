<?php

require_once 'functions.php';

($getIdentity)? Redirect::to('index.php') : '' ;

$vids = explode(',', $mtbOptions['welcomevids']);
$max = sizeof($vids) - 1;
$index = rand(0, $max);
$play = $mytube->getVideo($vids[$index]);

$card = mountCard($play['vid_author_id'], '');

$Errors = false;



if (Input::exists()) {

	if (Token::check(Input::get('token'))) {

	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'username'	=> array(
			'required'	=>	true,
			'min'	=>	5,
			'max'	=>	20,
			'unique'	=>  array( 
				'table' => 'mtb_users' ,
				'field'	=> 'username' 
			),
			'alphanumeric' => true
		),
		'password'	=> array(
			'required' => true,
			'min'	=>	6
		),
		'repeat_password'	=> array(
			'required' => true,
			'matches' => 'password'
		),
		'first_name'	=> array(
			'required' => true,
			'max'	=> 50,
			'min'	=>	4
		),
		'last_name'	=> array(
			'required' => true,
			'max'	=> 50,
			'min'	=>	4
		)
	));

	if($validation->passed()){
		$user = new User();
		$sanitizer = new Sanitize();
		$salt = Hash::salt(32);
		try {
			$user->create(array(
				'username' => $sanitizer->clean(Input::get('username')),
				'password' => Hash::make(Input::get('password'), $salt),
				'salt' => $salt,
				'first_name' => $sanitizer->clean(Input::get('first_name')),
				'last_name' => $sanitizer->clean(Input::get('last_name')),
				'joined' => date('Y-m-d  H:i:s'),
				'group' => 0,
			));

		sleep(5);

		$login = $user->login(Input::get('username'),Input::get('password'), true );

		if ($login) {

			Redirect::to('index.php');
		}

		

		} catch (Exception $e) {
			echo $e->getMessage();
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

echo $Theme->render('register.html', array(
	'video'	=>$play,
	'errors'=> $Errors,
	'card'	=> $card,
	'username'	=> Input::get('username'),
	'password' => Input::get('password'),
	'repeat_password' => Input::get('password_again'),
	'first_name' => Input::get('first_name'),
	'last_name' => Input::get('last_name')
));


?>