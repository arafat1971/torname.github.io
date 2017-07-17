<?php

require_once 'functions.php';

if (Session::exists('editprofile')) {
	$flash = Session::get('editprofile');
}

($getIdentity)?  : Redirect::to('index.php') ;

$ad_codes = $mytube->getAdCodes($getIdentity['id']);

$Alltemplates = array();
$alllangs = array();

foreach (scandir(__DIR__ . '/../../core/language/') as $result) {
	if ($result === '.' or $result === '..') continue;

	 if (is_file( __DIR__ . '/../../core/language/' . $result)) {

	 	$result = str_replace('.php', '', $result);

	 	array_push($alllangs, $result);
	 }
}


$templates = __DIR__ . '/../../templates';

foreach ( scandir($templates) as $result) {

    if ($result === '.' or $result === '..') continue;

    if (is_dir($templates . '/' . $result)) {
      	
      	if (file_exists($templates.'/'.$result.'/theme.ini.php')) {

   			array_push($Alltemplates, array(

   				'name' => $result,
   				'thumb' => $templates.'/'.$result.'/img/thumb.png'
   			));

		} 
    }
}
$cleaner = new Sanitize();
$Errors = array();
$go = false;
if (Input::exists()) {
		if (Token::check(Input::get('token'))) {

			$data = array();

				foreach ($_POST as $key => $value) {

					if ($value != '' && $key != 'token') {
						$key =  str_replace('_field', '', $key);
						$data[$key] = $cleaner->clean($value);

						$go = true;
					}
				}

				// comment this if block on production
							
							if ($getIdentity['username'] == 'phptube') {
								
								$Errors = false;

								Session::flash('admindashboard', $lang['Demo Settings update success message']);

								header('Location: '.$_SERVER['REQUEST_URI']);

								exit();

							}
							
				// end if block

						if($go){
							$cont = 0 ;
							foreach ($data as $key => $value) {

								$qqry = 'UPDATE `mtb_users_settings` SET `value` = ? WHERE `mtb_users_settings`.`option_key` = ? AND `mtb_users_settings`.`user_id` = ? LIMIT 1';
								if( $_db->rawQuery($qqry, array($value,$key,$getIdentity['id']))){
									$cont = 1;
								} else {
									$qqry = 'INSERT INTO `mtb_users_settings`(`user_id`,`option_key`,`value`) VALUES (?,?,?)';
									if( $_db->rawQuery($qqry, array($getIdentity['id'],$key,$value))){
										$cont = 1;
									}

								}

							if ($cont = 1) {
								
									$Errors = false;
									Session::flash('editprofile', $lang['Profile update success message']);
									 header('Location: '.$_SERVER['REQUEST_URI']);
										}
							}
						}

	} else {

		 array_push($Errors, array('field' => 'token', 'message' => 'invalid token'));
	}
}

	/*$token = Token::generate();
	Session::put(config::get('sessions/token_name'), $token );*/

echo $Theme->render('settings.html', array(
	'flash' => $flash,
	'options' => $mtbOptions,
	'user' => $getIdentity,
	'ad_codes' => $ad_codes,
	'templates' => $Alltemplates,
	'langs'	=> $alllangs,
));

Session::delete('editprofile');