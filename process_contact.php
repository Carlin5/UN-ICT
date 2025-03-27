<?php
require_once 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $date = date('Y-m-d H:i:s');

    try {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message, date) VALUES (:name, :email, :message, :date)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        // Send email notification
        $to = "legal@un-ict.africa";
        $subject = "New Contact Form Submission";
        $email_message = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

        mail($to, $subject, $email_message, $headers);

        echo json_encode(['success' => true, 'message' => 'Thank you for your message. We will get back to you soon!']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'There was an error sending your message. Please try again later.']);
    }
}
?> 