<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Log the received data
error_log("Received data: " . print_r($data, true));

if (!$data) {
    error_log("Invalid JSON data received");
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data', 'details' => json_last_error_msg()]);
    exit;
}

// Ensure the data directory exists
$dataDir = 'data';
if (!file_exists($dataDir)) {
    if (!mkdir($dataDir, 0777, true)) {
        error_log("Failed to create data directory");
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create data directory']);
        exit;
    }
}

// Read existing messages
$messagesFile = $dataDir . '/messages.json';
$messages = ['messages' => []];
if (file_exists($messagesFile)) {
    $existingData = file_get_contents($messagesFile);
    if ($existingData !== false) {
        $messages = json_decode($existingData, true) ?: ['messages' => []];
    }
}

// Add new message
$messages['messages'][] = $data;

// Save back to file
$result = file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
if ($result === false) {
    error_log("Failed to write to messages.json");
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to save message',
        'details' => error_get_last()
    ]);
} else {
    echo json_encode(['success' => true]);
}
?> 