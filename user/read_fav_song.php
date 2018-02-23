<?php
// use GET URL/user/read_fav_song.php?user_id=$user_id&song_id=$song_id
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
 
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/song.php';

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
	 
	// create array
	$result = $user->readFavSong();
} catch(Exception $e) {
	$result = array(
		"Error" => $e->getMessage()
	);
}
 
// make it json format
print_r(json_encode($result));