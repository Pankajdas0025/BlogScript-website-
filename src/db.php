<?php
include 'config.php';
$conn = new mysqli("localhost", "root", $dbpass , "blog");  //  for localhost
if ($conn->connect_error){die("Connection failed: " . $conn->connect_error);}
?>
