<?php
require_once 'functions.php';

if(!$getIdentity) Redirect::to('index.php') ;
$offset = 0;
$notifications = $mytube->getNotifications($getIdentity['id'],$getIdentity['username'], $offset , 0);

echo $Theme->render('notifications.html', array(
	'user' => $getIdentity,
	'notifications'	=>	$notifications,
));
