<?php 
require_once('user.php');
require_once('song.php');


if(!empty($_GET))
	echo json_encode($_GET);

if(!empty($_POST))
	echo json_encode($_POST);
?>