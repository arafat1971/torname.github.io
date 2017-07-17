<?php

require_once 'functions.php';

$Errors = false;


if (Input::exists('get')) {

	if (Token::check(Input::get('token'))) {
		

		$validate = new Validate();
		$validation = $validate->check($_GET,array(
		'q' => array('required' => true)
		));

			
		
		if($validation->passed()){


		
		$videos = makeSearchResultVideos(Input::get('q'));
		
		$tags = 'Search Tags: ';
				
		foreach ($videos['tags'] as $tag) {
			$tags .= '<a href="'.$paths['base_path'].'/search.php?q='.$tag.'&token='.$token.'" >'.$tag.'</a>	| ';
		}

			if (strlen($videos['html']) == 1) {
				
				
				$videos['html'] .= '</br>Nothing Found ... ' ;
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
		array_push($Errors, array('field' => 'invalidtoken', 'message' => 'invalid token'));
		
		$videos['html'] = 'Invalid Token';
		$tags = '';
	}

}
if ($_GET['q'] == '') {
	$tags = '';			
	$videos['html'] = 'Search Bar Is Empty';
}

echo $Theme->render('search.html', array(
	'user' => $getIdentity,
	'errors'=> $Errors,
	'tags'	=>	$tags,
	'q' => Input::get('q'),
	'videos' => $videos['html'],
));

