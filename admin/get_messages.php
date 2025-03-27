<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $messages_file = '../messages.txt';
    
    if (!file_exists($messages_file)) {
        echo json_encode([]);
        exit;
    }

    $messages = [];
    $lines = file($messages_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lines === false) {
        throw new Exception('Failed to read messages file');
    }

    foreach ($lines as $index => $line) {
        $parts = explode('|', $line);
        if (count($parts) === 4) {
            $messages[] = [
                'id' => $index,
                'date' => $parts[0],
                'name' => $parts[1],
                'email' => $parts[2],
                'message' => $parts[3]
            ];
        }
    }

    // Sort messages by date (newest first)
    usort($messages, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    echo json_encode($messages);

} catch (Exception $e) {
    error_log('Error in get_messages.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load messages: ' . $e->getMessage()]);
}
?> 