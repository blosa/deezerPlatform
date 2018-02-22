<?php
// use POST URL/user/delete_fav_song.php?user_id=$user_id&song_id=$song_id
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/user.php';
include_once '../objects/song.php';
 
$database = new Database();
 
$user = new user($database);

// set user property values
$user->user_id = $_GET['user_id'];
$song_id = $_GET['song_id'];

// delete
if($user->delFavSong($song_id)){
	echo json_encode(array("message" => 'Song #' . $song_id . ' deleted for user #' . $user->user_id));
}
// if unable to create the user, tell the user
else{
	echo json_encode(array("message" => 'Unable to delete song #' . $song_id . ' to user #' . $user->user_id));
}