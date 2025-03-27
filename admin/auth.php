<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set secure session parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

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

// Add a small delay to prevent brute force attacks
sleep(1);

if ($data['username'] === $valid_username && $data['password'] === $valid_password) {
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    $_SESSION['authenticated'] = true;
    $_SESSION['username'] = $data['username'];
    $_SESSION['last_activity'] = time();
    
    echo json_encode(['success' => true]);
} else {
    // Log failed login attempts
    error_log("Failed login attempt for username: " . $data['username']);
    
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
}
?> 