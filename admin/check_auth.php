<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is authenticated
$authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

echo json_encode([
    'authenticated' => $authenticated,
    'username' => $authenticated ? $_SESSION['username'] : null
]);
?> 