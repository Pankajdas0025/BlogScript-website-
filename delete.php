

<!-- Delete Post -->
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
$post = $conn->query("SELECT * FROM posts WHERE ID=$id")->fetch_assoc();
if ($post) {
    //Remove image file from uploads folder ---------------------------
    $image = $post['post_image'];
    if ($image != "default.png") {
        unlink("uploads/posts/$image");
    }
    //Delete post from database --------------------------------------
    $conn->query("DELETE FROM posts WHERE ID=$id");
}
header("Location:admin");
?>