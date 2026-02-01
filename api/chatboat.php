<?php
/**
 * BlogScript - Chatbot API Endpoint
 * Rule-based FAQ chatbot
 */

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

header('Content-Type: application/json');

$raw_message = isset($_POST['message']) ? trim($_POST['message']) : '';
// Limit input length to avoid abuse
if (mb_strlen($raw_message) > 500) {
    $raw_message = mb_substr($raw_message, 0, 500);
}
$message = strtolower($raw_message);
$db = $conn;

if (empty($raw_message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message is required']);
    exit;
}

// Rule-based chatbot responses
$reply = '';
$suggestions = [];

// Greeting
if (preg_match('/(hello|hi|hey|greetings|good morning|good afternoon|good evening)/i', $message)) {
    $reply = 'Hello! 👋 Welcome to BlogScript. How can I help you today?';
    $suggestions = [
        'How to create a account in BlogScript',
        'How to register ? ',
        'Popular items'
    ];
}
// How to search items
elseif (preg_match('/(how.*search|search|find|browse|explore|look.*for)/i', $message)) {
    $reply = 'To search for items on BlogScript . Visit the Home page or Explore page. Use the search bar to find specific items. Filter by category or condition\n4. Click on any item to view full details\. Check seller ratings and contact information. Message the seller for more details . You can also save items to your favorites list!';
    $suggestions = [
        '',
        'How reset password',
        'Contact support'
    ];
}
// How to post an item
elseif (preg_match('/(how.*post|post.*item|create.*post|sell|listing|share.*item)/i', $message)) {
    $reply = 'To post an item for sale on CampusXchange:\n\n1. Create an account or login\n2. Click "Create Post" or "Sell Item" in the navigation\n3. Fill in the item details:\n   - Title and description\n   - Price\n   - Category\n   - Item condition\n4. Upload photos/videos of the item\n5. Set availability and contact details\n6. Submit for approval\n\nYour listing will appear once approved by our moderation team!';
    $suggestions = [
        'How to search items?',
        'How much time take for approved a post',
        'Contact support'
    ];
}
// How pricing/trending works
elseif (preg_match('/(trending|popular|algorithm|score|rank|price|payment|how.*works)/i', $message)) {
    $reply = 'Our popular items algorithm ranks listings based on:\n\n📊 Activity Score:\n- Views (1 point each)\n- Inquiries (5 points each)\n- Favorites (7 points each)\n\n⏰ Recency Boost:\n- Posted within 24 hours: 40% boost\n- Posted within 7 days: 15% boost\n- Older listings: standard ranking\n\nThis ensures both active and fresh listings get visibility!';
    $suggestions = [
        'Popular items today',
        'How to se?',
        'How to post an item?'
    ];
}
// How to reset password
elseif (preg_match('/(reset.*password|forgot.*password|change.*password|password.*help)/i', $message)) {
    $reply = 'To reset your password:\n\n1. Click "Forgot password?" on the login page\n2. Enter your email address\n3. You\'ll receive a password reset token\n4. Click the reset link or enter the token\n5. Create a new password (min 8 chars, with uppercase, lowercase, number, and special char)\n6. Login with your new password\n\nPassword requirements: At least 8 characters including uppercase, lowercase, number, and special character (@$!%*?&)';
    $suggestions = [
        'Contact support',
        'How to use prompts?'
    ];
}
// Popular items today
elseif (preg_match('/(top.*item|trending.*today|popular|best|featured)/i', $message)) {
    $reply = getPopularItemsMessage($db);
    $suggestions = [
        'How to search items?',
        'How to post an item?',
        'Categories list'
    ];
}

// Contact support
elseif (preg_match('/(contact|support|help|email|problem|issue|bug|report)/i', $message)) {
    $reply = 'For support, you can:\n\n📧 Email: support@campusxchange.com\n💬 Chat with us here\n📝 Report issues using the flag/report button on any listing\n\nOur team typically responds within 24 hours. Please provide as much detail as possible about your issue!';
    $suggestions = [
        'How to search items?',
        'How to post an item?',
        'Popular items today'
    ];
}
// Default response
else {
    $reply = 'I\'m not sure I understood that. Here are some things I can help with:\n\n• How to search items\n• How to post an item\n• How pricing works\n• Popular items today\n• Categories list\n• Password reset\n• Contact support\n\nFeel free to ask any of these questions!';
    $suggestions = [
        'How to search items?',
        'How to post an item?',
        'Popular items today'
    ];
}

echo json_encode([
    'success' => true,
    'reply' => $reply,
    'suggestions' => array_values((array)$suggestions)
], JSON_UNESCAPED_UNICODE);

/**
 * Get top 5 popular items message
 */
function getPopularItemsMessage($db) {
    try {
        $stmt = $db->prepare('
            SELECT p.id, p.title, p.price, p.views,
                   (SELECT COUNT(*) FROM saves WHERE post_id = p.id) as favorite_count,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as inquiry_count
            FROM posts p
            WHERE p.status = "published" OR p.status = "approved"
            ORDER BY p.views DESC, p.created_at DESC
            LIMIT 5
        ');
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($items)) {
            return 'No items available yet. Check back soon!';
        }

        $message = "🔥 Top 5 Popular Items Today:\n\n";
        foreach ($items as $index => $item) {
            $message .= ($index + 1) . '. ' . htmlspecialchars($item['title']) . ' - ₹' . htmlspecialchars($item['price']) . '\n';
            $message .= '   👁️ ' . $item['views'] . ' views | ❤️ ' . $item['favorite_count'] . ' favorites | 💬 ' . $item['inquiry_count'] . " inquiries\n\n";
        }

        $message .= "Click on any item in the Explore page to view full details!";
        return $message;
    } catch (Exception $e) {
        return 'Unable to fetch popular items. Please try again later.';
    }
}

