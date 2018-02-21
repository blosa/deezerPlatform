<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new user($db);
 
// set ID property of user to be edited
$user->user_identifiant = isset($_GET['user_identifiant']) ? $_GET['user_identifiant'] : die();
 
// read the details of user to be edited
$user->readOne();
 
// create array
$user_arr = array(
    "id" =>  $user->user_id,
    "user_name" => $user->user_name,
    "user_email" => $user->user_email,
    "user_identifiant" => $user->user_identifiant
);
 
// make it json format
print_r(json_encode($user_arr));
?>