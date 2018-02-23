<?php
// use GET URL/user/read_one.php?user_id=$user_id
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
 
include_once '../config/database.php';
include_once '../objects/user.php';
 
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
 
	// read the details of user to be edited
	$user->readOne();
	 
	// create array
	$result = array(
	    "user_id" =>  $user->user_id,
	    "user_name" => $user->user_name,
	    "user_email" => $user->user_email
	);
} catch(Exception $e) {
	$result = array(
		"Error" => $e->getMessage()
	);
}
 
// make it json format
print_r(json_encode($result));
?>