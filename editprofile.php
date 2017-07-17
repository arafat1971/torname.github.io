<?php

require_once 'functions.php';

if (Session::exists('editprofile')) {
	$flash = Session::get('editprofile');
	Session::delete('editprofile');
}

$__jsErrors = array();

($getIdentity)?  : Redirect::to('index.php') ;
	
if (Input::exists()) {
				if (Token::jscheck(Input::get('token'))) {
					
					$validate = new Validate();
					$validation = $validate->check($_POST,array(
					'avatar_field' => array(
						'min'	=>	10,
						'required'	=>	true
						)
					));

				
					
					if($validation->passed()){
						
						$user = new User($getIdentity['id']);
						$clean = new Sanitize();

						$data = array();

						foreach ($_POST as $key => $value) {
							if ($value != '' && $key != 'token') {
								$key =  str_replace('_field', '', $key);
								$data[$key] = $value;
							}
						}

						var_dump($data); die();

						// comment this if block on production
							
							if ($getIdentity['username'] == 'phptube' or $getIdentity['username'] == 'JohnDoe') {
								
								$__jsErrors = false;

								Session::flash('editprofile', $lang['Demo Profile update success message']);
								
								echo json_encode($__jsErrors);

								exit();

							}
							
						// end if block

					  /*  list($type, $imageData) = explode(';', $data['avatar']);
					    list(,$extension) = explode('/',$type);
					    list(,$imageData)      = explode(',', $imageData);
					    $fileName = uniqid().'.'.$extension;
					    $avatar = base64_decode($imageData);

					    $oldavatar = __DIR__.'/../../storage/avatar/'.basename($getIdentity['avatar']);*/



					    /*if($getIdentity['avatar'] != $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/default.png' && file_exists($oldavatar)){
					    	unlink($oldavatar);
					    }*/
/*
					    if(file_put_contents(__DIR__.'/../../storage/avatar/'.$fileName, $avatar)){

					    	$data['avatar'] = $mtbOptions['protocol'].'://'.Config::get('baseUrl').'storage/avatar/'.$fileName;

							if( !empty($data) and $user->update($data, $getIdentity['id'])){
								
								$__jsErrors = false;

								Session::flash('editprofile', $lang['Profile update success message']);
							}
					    	
					    	header("Refresh:0");
					    	
					    }	else{

							array_push($__jsErrors, array('field' => 'avatar_field', 'message' => 'wight permition error'));
						}*/



					} else{

						$x = 0 ;
						foreach ($validation->errors() as $errors ) {
						
								array_push($__jsErrors, $errors);

						}

					}
				} else {

					 array_push($__jsErrors, array('field' => 'avatar_field', 'message' => 'invalid token'));
				}

			}
echo $Theme->render('editprofile.html', array(
	'flash' => $flash,
	'user' => $getIdentity
));

