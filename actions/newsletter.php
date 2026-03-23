<?php
include '../src/db.php';

header('Content-Type: application/json');
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
// Validate email
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

// Check if already exists
$stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'You are already subscribed.'
    ]);
    exit;
}

// Insert email
$stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Subscribed successfully!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong. Try again.'
    ]);
}