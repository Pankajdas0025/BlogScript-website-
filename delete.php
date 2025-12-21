
<?php

session_start();

if (!isset($_SESSION['email']))
    {
    echo "<script>alert('Plesase Login ');</script>";
    header("Location:register");
    exit();
    }

include 'src/db.php';
include 'src/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM posts WHERE ID=$id");
header("Location:admin");
?>