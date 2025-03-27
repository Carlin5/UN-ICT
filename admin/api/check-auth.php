<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

// Get authorization header
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

if (empty($token)) {
    http_response_code(401);
    echo json_encode(['authenticated' => false]);
    exit;
}

try {
    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT id FROM admin_users WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['authenticated' => !empty($user)]);
} catch(PDOException $e) {
    error_log("Auth check error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['authenticated' => false]);
}
?> 