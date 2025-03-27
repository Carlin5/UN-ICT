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

    // Get messages
    $stmt = $conn->query("SELECT * FROM messages ORDER BY date DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch(PDOException $e) {
    error_log("Error fetching messages: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch messages']);
}
?> 