<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $messages_file = '../messages.txt';
    
    if (!file_exists($messages_file)) {
        echo json_encode(['success' => true]);
        exit;
    }

    // Clear the messages file
    if (file_put_contents($messages_file, '') === false) {
        throw new Exception('Failed to clear messages file');
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 