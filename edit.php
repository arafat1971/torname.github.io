<?php
require_once  'functions.php';

($getIdentity)?  : Redirect::to('index.php') ;
$step = 0;
$VideoData = array();
$validationErrors = array();
$categories = $_db->get('mtb_category', 30);
if (isset($_GET['vid'])) {
	
				
	$play = $mytube->getVideo($_GET['vid']);
	$videoPlayer = false;

	if ($play) {
		$videoPlayer = makeVideoPlayer($play, 'index');
	} 

	if(!$getIdentity or $getIdentity['id'] != $play['vid_author_id']) Redirect::to('all.php');

	$inputs = array(

		'title'	=>	$play['title'],
		'description'	=>	$play['description'],
		'category_id'	=>	$play['category_id'],
		'thumbnail'		=>	$play['vid_thumbnail'],
		);
}	else {

	Redirect::to('all.php');

}


if (Input::exists()) {
		if (Token::check(Input::get('token')) or $play['is_active'] == 0 ) {
			
			$validate = new Validate();
			$validation = $validate->check($_POST,array(
			'video_title_field' => array(
				'required' => true,
				'min'	=>	20,
				'max'	=> 255
				),
			'thumbnail'	=>	array(
				'required'	=>	true,
				),
				)
			);

			if($validation->passed()){

				if ($play['source'] == 'local') {

					$cleaner = new Sanitize();

					$salt = Hash::salt(32);
					$new_name = Hash::make(uniqid(), $salt);

					list($type, $imageData) = explode(';', Input::get('thumbnail'));
				    list(,$extension) = explode('/',$type);
				    list(,$imageData) = explode(',', $imageData);
				    $thumbnailfileName = uniqid().'.'.$extension;

				    $thumbnail_data = base64_decode($imageData);
				    
				     $oldthumbnail = __DIR__.'/../../storage/avatar/'.$inputs['thumbnail'];

						    if(file_exists($oldthumbnail)){
						    	unlink($oldthumbnail);
						    }



				    if(file_put_contents(__DIR__.'/../../storage/thumbnails/'.$thumbnailfileName, $thumbnail_data)){
				    	$thumbnailfileName = $paths['base_path'].'/storage/thumbnails/'.$thumbnailfileName;
				    }	else{

						exit();
					}
				} else {
					$thumbnailfileName 	= $play['vid_thumbnail'];
				}

				
				
				$VideoData = array(
					"title"			=> Input::get('video_title_field'),
					"description"	=> Input::get('video_description_field'),
					"category_id"	=> Input::get('category'),
					"is_active"	=> 1,
				);

				if($play['source'] == 'local'){

						$VideoData['vid_thumbnail']	= $thumbnailfileName;
				}

				if( $mytube->updateVideo($_GET['vid'], $VideoData) ){

						Redirect::to('profile.php');
					
				}

			} else {
				
				foreach ($validation->errors() as $error ) {

					$validationErrors[$error['field']] = $error['message'] ;
				}

			}

		} else {

			 var_dump($token) ;
			 echo'<br>';
			var_dump($_SESSION['token']);
			 echo'<br>';
			 var_dump(Input::get('token'));


		}
	}

echo $Theme->render('edit.html', array(

	'videoplayer'	=>	$videoPlayer,
	'video'	=> $play,
	'Errors' => $validationErrors,
	'Inputs'	=>	$inputs,
	'step' => $step,
	'categories' => $categories,
));


?>