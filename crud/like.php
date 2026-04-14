<?php
include '../src/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo "login";
    exit;
}

$user_id = $_SESSION['id'];
$blog_id = (int) $_POST['blog_id'];

// Check already liked
$check = $conn->query("SELECT * FROM likes WHERE blog_id=$blog_id AND user_id=$user_id");

if ($check->num_rows > 0) {
    // Unlike
    $conn->query("DELETE FROM likes WHERE blog_id=$blog_id AND user_id=$user_id");
    echo "unliked";
} else {
    // Like
    $conn->query("INSERT INTO likes (blog_id, user_id) VALUES ($blog_id, $user_id)");
    echo "liked";
}