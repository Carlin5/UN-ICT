<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing credentials']);
    exit;
}

// In a production environment, you should:
// 1. Use environment variables for credentials
// 2. Hash passwords using password_hash()
// 3. Use a secure database for user management
// 4. Implement rate limiting
// 5. Use HTTPS
$valid_username = 'admin';
$valid_password = 'admin'; // In production, use a hashed password

if ($data['username'] === $valid_username && $data['password'] === $valid_password) {
    $_SESSION['authenticated'] = true;
    $_SESSION['username'] = $data['username'];
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
}
?> 