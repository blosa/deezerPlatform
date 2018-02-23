<?php
// use POST URL/user/add_fav_song.php?user_id=$user_id&song_id=$song_id
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/song.php';
 
$database = new Database();
 
$user = new user($database);

// set user property values
$user->user_id = $_GET['user_id'];
$song_id = $_GET['song_id'];

// add song
if($user->addFavSong($song_id)){
	echo json_encode(array("message" => 'Song #' . $song_id . ' added to user #' . $user->user_id));
}
// if unable to create the user, tell the user
else{
	echo json_encode(array("message" => 'Unable to attach song #' . $song_id . ' to user #' . $user->user_id));
}




try{
	// get database connection
	$database = new Database();
	//$db = $database->getConnection();
	 
	// prepare user object
	$user = new user($database);
	// set ID property of user to be edited
	if(!isset($_GET['user_id'])) {
		throw new Exception('Missing user_id argument');
	}
	$user->user_id = $_GET['user_id'];
	 
	if(!isset($_GET['song_id'])) {
		throw new Exception('Missing song_id argument');
	}
	$song_id = $_GET['song_id'];

	// add song
	if($user->addFavSong($song_id)){
		$result = array(
			"message" => 'Song #' . $song_id . ' added to user #' . $user->user_id
		);
	}
	// if unable to create the user, tell the user
	else{
		$result = array(
			"message" => 'Unable to attach song #' . $song_id . ' to user #' . $user->user_id
		);
	}
} catch(Exception $e) {
	$result = array(
		"Error" => $e->getMessage()
	);
}
 
// make it json format
print_r(json_encode($result));