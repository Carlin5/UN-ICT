<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get message ID
$message_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$message_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Message ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
    $stmt->execute([$message_id]);
    $message = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$message) {
        http_response_code(404);
        echo json_encode(['error' => 'Message not found']);
        exit;
    }

    // Format date
    $message['date'] = date('Y-m-d H:i:s', strtotime($message['date']));

    // Return message data
    header('Content-Type: application/json');
    echo json_encode($message);

} catch (PDOException $e) {
    error_log("Error fetching message: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}
?> 