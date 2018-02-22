<?php
// use GET URL/user/read_fav_song.php?user_id=$user_id&song_id=$song_id
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/song.php';
 
// get database connection
$database = new Database();
//$db = $database->getConnection();
 
// prepare user object
$user = new user($database);
// set ID property of user to be edited
$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();
 
echo json_encode($user->readFavSong());
?>