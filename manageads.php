<?php

require_once 'functions.php';

if (Session::exists('editprofile')) {
	$flash = Session::get('editprofile');
}

($getIdentity)?  : Redirect::to('index.php') ;

$ad_codes = $mytube->getAdCodes($getIdentity['id']);	

echo $Theme->render('manageads.html', array(
	'flash' => $flash,
	'user' => $getIdentity,
	'ad_codes' => $ad_codes
));

Session::delete('editprofile');