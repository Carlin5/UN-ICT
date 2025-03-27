<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    // Get and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validate input
    if (!$name || !$email || !$message) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message, date) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $message]);

    // Send email notification
    $to = 'legal@un-ict.africa';
    $subject = 'New Contact Form Submission';
    
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #f8f9fa; padding: 20px; border-radius: 5px; }
            .content { padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #333; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <span class='label'>Date:</span> " . date('Y-m-d H:i:s') . "
                </div>
                <div class='field'>
                    <span class='label'>Name:</span> " . htmlspecialchars($name) . "
                </div>
                <div class='field'>
                    <span class='label'>Email:</span> " . htmlspecialchars($email) . "
                </div>
                <div class='field'>
                    <span class='label'>Message:</span><br>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
            </div>
        </div>
    </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    $mailSent = mail($to, $subject, $emailBody, $headers);

    if (!$mailSent) {
        error_log("Failed to send email notification for contact form submission");
    }

    // Redirect with success message
    header('Location: index.html?status=success&message=Message sent successfully');
    exit;

} catch (Exception $e) {
    // Log error
    error_log("Contact form error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: index.html?status=error&message=' . urlencode($e->getMessage()));
    exit;
}
?> 