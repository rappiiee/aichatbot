<?php
/**
 * AJAX endpoint: receives a user chat message, saves it, generates a bot
 * reply via chatbot_logic.php, saves the reply, and returns it as JSON.
 */
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/chatbot_logic.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['reply' => 'Your session has expired. Please log in again.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['message'])) {
    http_response_code(400);
    echo json_encode(['reply' => 'No message received.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$message = clean_input($_POST['message']);

// Save the user's message
$stmt = $conn->prepare("INSERT INTO chat_history (user_id, sender, message) VALUES (?, 'user', ?)");
$stmt->bind_param('is', $user_id, $message);
$stmt->execute();

// Generate the bot's reply
$reply = get_bot_reply($message, $conn);

// Save the bot's reply
$stmt = $conn->prepare("INSERT INTO chat_history (user_id, sender, message) VALUES (?, 'bot', ?)");
$stmt->bind_param('is', $user_id, $reply);
$stmt->execute();

echo json_encode(['reply' => $reply]);
