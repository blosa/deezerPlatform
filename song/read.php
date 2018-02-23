<?php
// use GET URL/song/read.php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/song.php';
 
// instantiate database and song object
$database = new Database();
//$db = $database->getConnection();
 
// initialize object
$song = new song($database);
 
// query products
$stmt = $song->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $song_arr=array();
    $song_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        array_push($song_arr["records"], $row);
    }
 
    echo json_encode($song_arr);
}
 
else{
    echo json_encode(
        array("message" => "No songs found.")
    );
}