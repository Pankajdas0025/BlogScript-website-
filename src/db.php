<?php
include 'config.php';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);  //  for localhost
if ($conn->connect_error){die("Connection failed: " . $conn->connect_error);}

?>
