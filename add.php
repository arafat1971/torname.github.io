<?php
require_once  'functions.php';

($getIdentity)?  : Redirect::to('index.php') ;

$categories = $_db->get('mtb_category', 30);
$step = 0;
$VideoData = array();
$validationErrors = array();

$inputs = array(
	'upload' => Input::get('upload'),
	'title'	=>	Input::get('video_title_field'),
	'thumbnail'	=>	Input::get('thumbnail'),
	'duration'	=>	Input::get('duration'),
	'description'	=>	Input::get('video_description_field'),
	'category_id'	=>	(int) Input::get('category') 
	);



if (Input::exists()) {

		if (Token::check(Input::get('token'))) {
			
			$validate = new Validate();

			$validation = $validate->check($_POST,array(
			'video_title_field' => array(
				'required' => true,
				'min'	=>	20,
				'max'	=> 255
				),
			'upload' => array(
				'required'	=>	true,
				),
			'thumbnail'	=>	array(
				'required'	=>	true,
				),
			'duration'	=>	array(
				'required'	=>	true,
				),

			));

			if($validation->passed()){

				$cleaner = new Sanitize();
				$salt = Hash::salt(32);
				$new_name = Hash::make(uniqid(), $salt);

				list($type, $imageData) = explode(';', $inputs['thumbnail']);
			    list(,$extension) = explode('/',$type);
			    list(,$imageData)      = explode(',', $imageData);
			    $thumbnailfileName = uniqid().'.'.$extension;

			    $thumbnail_data = base64_decode($imageData);
			    
			    if(file_put_contents(__DIR__.'/../../storage/thumbnails/'.$thumbnailfileName, $thumbnail_data)){
			    	$thumbnailfileName = $paths['base_path'].'/storage/thumbnails/'.$thumbnailfileName;
			    }	else{

					exit();
				}
			    				
				

				$videoData = array(
								"title" => $cleaner->clean($inputs['title']),
								"description"	=> $cleaner->clean($inputs['description']),
								"is_active"		=> 1,
								"category_id"	=> $cleaner->clean($inputs['category_id']),
								"vid_thumbnail"	=> $thumbnailfileName,
								"vid_duration"	=> $cleaner->clean($inputs['duration'])
							);

				if( $mytube->updateLastVideo($getIdentity['id'], $videoData) ){

						if ($mytube->newVideoActivity($getIdentity,'local-post')) {
						
							Redirect::to('profile.php');
						}
				}

			} else {
				
				foreach ($validation->errors() as $error ) {

					$validationErrors[$error['field']] = $error['message'] ;
				}

			}

		
		
		}
	}
	
echo $Theme->render('add.html', array(
	//'input' => Input::get('videourl'),
	'Errors' => $validationErrors,
	'Inputs'	=>	$inputs,
	'step' => $step,
	'categories' => $categories

));

?>