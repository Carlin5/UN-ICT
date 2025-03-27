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

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
    exit;
}

try {
    $messages_file = '../messages.txt';
    
    if (!file_exists($messages_file)) {
        throw new Exception('Messages file not found');
    }

    $lines = file($messages_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lines === false) {
        throw new Exception('Failed to read messages file');
    }

    // Remove the specified message
    unset($lines[$data['id']]);
    
    // Reindex the array
    $lines = array_values($lines);
    
    // Write the updated messages back to the file
    if (file_put_contents($messages_file, implode("\n", $lines) . "\n") === false) {
        throw new Exception('Failed to write to messages file');
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log('Error in delete_message.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 