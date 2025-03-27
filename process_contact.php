<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $date = date('Y-m-d H:i:s');

    try {
        // Create messages directory if it doesn't exist
        $messages_dir = __DIR__;
        if (!file_exists($messages_dir)) {
            mkdir($messages_dir, 0777, true);
        }

        // Prepare the message line
        $message_line = $date . '|' . $name . '|' . $email . '|' . $message . "\n";

        // Append the message to the file
        if (file_put_contents('messages.txt', $message_line, FILE_APPEND | LOCK_EX) === false) {
            throw new Exception('Failed to write message to file');
        }

        // Send email notification
        $to = "legal@un-ict.africa";
        $subject = "New Contact Form Submission";
        $email_message = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

        mail($to, $subject, $email_message, $headers);

        echo json_encode(['success' => true, 'message' => 'Thank you for your message. We will get back to you soon!']);
    } catch(Exception $e) {
        error_log('Contact form error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'There was an error sending your message. Please try again later.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?> 