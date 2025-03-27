<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

// Check authentication
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

if (empty($token)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$message_id = $data['message_id'] ?? 0;

if (empty($message_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message ID is required']);
    exit;
}

try {
    // Verify token
    $stmt = $conn->prepare("SELECT id FROM admin_users WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($user)) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Delete message
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = :id");
    $stmt->bindParam(':id', $message_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    error_log("Error deleting message: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete message']);
}
?> 