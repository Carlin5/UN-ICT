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

    // Ensure the data directory exists
    $dataDir = __DIR__ . '/data';
    if (!file_exists($dataDir)) {
        if (!mkdir($dataDir, 0777, true)) {
            throw new Exception('Failed to create data directory');
        }
    }

    // Read existing messages
    $messagesFile = $dataDir . '/messages.json';
    $messages = ['messages' => []];
    if (file_exists($messagesFile)) {
        $existingData = file_get_contents($messagesFile);
        if ($existingData !== false) {
            $decoded = json_decode($existingData, true);
            if ($decoded !== null) {
                $messages = $decoded;
            }
        }
    }

    // Add new message
    $messages['messages'][] = [
        'name' => $data['name'],
        'email' => $data['email'],
        'message' => $data['message'],
        'date' => date('Y-m-d H:i:s')
    ];

    // Save back to file
    $result = file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
    if ($result === false) {
        throw new Exception('Failed to save message');
    }

    echo json_encode(['success' => true, 'message' => 'Message saved successfully']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 