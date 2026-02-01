<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

header('Content-Type: application/json; charset=utf-8');

$raw_message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($raw_message === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message is required']);
    exit;
}

if (mb_strlen($raw_message) > 500) {
    $raw_message = mb_substr($raw_message, 0, 500);
}

$message = mb_strtolower($raw_message);

$reply = '';
$suggestions = [];

// Greeting
if (preg_match('/(hello|hi|hey|greetings|good morning|good afternoon|good evening)/i', $message)) {
    $reply = '<b>Hello!</b> Welcome to <b>BlogScript</b>. How can I help you today?';
    $suggestions = [
        'How to create an account?',
        'How to register?',
        'Popular items today'
    ];
}

// Search items
elseif (preg_match('/(how.*search|search|find|browse|explore|look.*for)/i', $message)) {
    $reply = '<b>To search on BlogScript:</b><br><br>
    1. Go to Home or Explore<br>
    2. Use the search bar<br>
    3. Filter by category or condition<br>
    4. Open an item to view full details<br>
    5. Check seller info and message if needed';
    $suggestions = [
        'How to post an item?',
        'How to reset password?',
        'Contact support'
    ];
}

// Post item
elseif (preg_match('/(how.*post|post.*item|create.*post|sell|listing|share.*item)/i', $message)) {
    $reply = '<b>To post an item on BlogScript:</b><br><br>
    1. Create an account or login<br>
    2. Click "Create Post" / "Sell Item"<br>
    3. Add details (title, description, price, category, condition)<br>
    4. Upload photos/videos<br>
    5. Add contact info<br>
    6. Submit<br><br>
    Your post will appear after approval.';
    $suggestions = [
        'How to search items?',
        'How long does approval take?',
        'Contact support'
    ];
}


// Reset password
elseif (preg_match('/(reset.*password|forgot.*password|change.*password|password.*help)/i', $message)) {
    $reply = '<b>To reset your password:</b><br><br>
    1. Click "Forgot password?" on login<br>
    2. Enter your email<br>
    3. Check email for reset link/token<br>
    4. Set a new password<br>
    5. Login again<br><br>
    ';
    $suggestions = [
        'Contact support',
        'How to register?'
    ];
}

// Support
elseif (preg_match('/(contact|support|help|email|problem|issue|bug|report)/i', $message)) {
    $reply = 'You can contact support using the <b>Contact Us</b> link in the footer section.';
    $suggestions = [
        'How to search items?',
        'How to post an item?',
        'Popular items today'
    ];
}

// Default
else {
    $reply = 'I didn’t fully get that. I can help with:<br><br>
    1. How to search items<br>
    2. How to post an item<br>
    3. How trending works<br>
    4. Password reset<br>
    5. Contact support';
    $suggestions = [
        'How to search items?',
        'How to post an item?',
        'Contact support'
    ];
}

echo json_encode([
    'success' => true,
    'reply' => $reply,
    'suggestions' => array_values(array_filter($suggestions))
], JSON_UNESCAPED_UNICODE);
