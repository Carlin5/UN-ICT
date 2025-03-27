<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting setup process...\n";

// Define the data directory path
$dataDir = __DIR__ . '/data';
$messagesFile = $dataDir . '/messages.json';

// Create data directory if it doesn't exist
if (!file_exists($dataDir)) {
    echo "Creating data directory...\n";
    if (mkdir($dataDir, 0777, true)) {
        echo "Data directory created successfully\n";
    } else {
        die("Failed to create data directory\n");
    }
} else {
    echo "Data directory already exists\n";
}

// Create messages.json if it doesn't exist
if (!file_exists($messagesFile)) {
    echo "Creating messages.json file...\n";
    $initialData = json_encode(['messages' => []], JSON_PRETTY_PRINT);
    if (file_put_contents($messagesFile, $initialData)) {
        echo "messages.json created successfully\n";
    } else {
        die("Failed to create messages.json\n");
    }
} else {
    echo "messages.json already exists\n";
}

// Set permissions
echo "Setting permissions...\n";
if (chmod($dataDir, 0777)) {
    echo "Directory permissions set successfully\n";
} else {
    echo "Warning: Failed to set directory permissions\n";
}

if (chmod($messagesFile, 0666)) {
    echo "File permissions set successfully\n";
} else {
    echo "Warning: Failed to set file permissions\n";
}

echo "\nSetup completed!\n";
echo "Data directory: " . $dataDir . "\n";
echo "Messages file: " . $messagesFile . "\n";
?> 