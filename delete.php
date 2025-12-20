
<?php
include 'src/db.php';
include 'src/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM posts WHERE ID=$id");
header("Location:admin");
?>