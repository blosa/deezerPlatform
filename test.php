<?php
$test = file_get_contents("http://localhost/deezerPlatform/toto/tata/user=moi&password=hello%20World");
echo $test;
echo "\n";
echo json_encode($_SERVER);
?>