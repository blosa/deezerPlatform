<?php
// use POST URL/song/read_one.php?song_id=$song_id
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/song.php';
 
// get database connection
$database = new Database();
//$db = $database->getConnection();
 
// prepare song object
$song = new song($database);
// set ID property of song to be edited
$song->song_id = isset($_GET['song_id']) ? $_GET['song_id'] : die();
 
// read the details of song to be edited
$song->readOne();
 
// create array
$song_arr = array(
    "song_id" =>  $song->song_id,
    "song_name" => $song->song_name,
    "song_duration" => $song->song_duration
);
 
// make it json format
print_r(json_encode($song_arr));
?>