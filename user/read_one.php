<?php
// use GET URL/user/read_one.php?user_id=$user_id
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
//$db = $database->getConnection();
 
// prepare user object
$user = new user($database);
// set ID property of user to be edited
$user->user_identifiant = isset($_GET['user_identifiant']) ? $_GET['user_identifiant'] : die();
 
// read the details of user to be edited
$user->readOne();
 
// create array
$user_arr = array(
    "user_id" =>  $user->user_id,
    "user_name" => $user->user_name,
    "user_email" => $user->user_email,
    "user_identifiant" => $user->user_identifiant
);
 
// make it json format
print_r(json_encode($user_arr));
?>