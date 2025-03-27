<?php
// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    if (empty($json)) {
        throw new Exception('No data received');
    }

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    // Validate required fields
    if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
        throw new Exception('Missing required fields');
    }

    // Format the message
    $message = sprintf(
        "%s|%s|%s|%s\n",
        date('Y-m-d H:i:s'),
        htmlspecialchars($data['name']),
        htmlspecialchars($data['email']),
        htmlspecialchars($data['message'])
    );

    // Save to messages.txt
    $result = file_put_contents('messages.txt', $message, FILE_APPEND);

    if ($result === false) {
        throw new Exception('Failed to save message');
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 