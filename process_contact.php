<?php
require_once 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $date = date('Y-m-d H:i:s');

    // Validate inputs
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?status=error&message=" . urlencode("Please fill in all fields with valid information."));
        exit;
    }

    try {
        // Store message in database
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message, date) VALUES (:name, :email, :message, :date)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        // Send email notification
        $to = "legal@un-ict.africa";
        $subject = "New Contact Form Submission from UN-ICT Website";
        
        $email_content = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background: #f9f9f9; }
                    .footer { text-align: center; padding: 20px; color: #666; }
                    .field { margin-bottom: 15px; }
                    .label { font-weight: bold; color: #2c3e50; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New Contact Form Submission</h2>
                    </div>
                    <div class='content'>
                        <div class='field'>
                            <span class='label'>Date:</span> {$date}
                        </div>
                        <div class='field'>
                            <span class='label'>Name:</span> {$name}
                        </div>
                        <div class='field'>
                            <span class='label'>Email:</span> {$email}
                        </div>
                        <div class='field'>
                            <span class='label'>Message:</span><br>
                            " . nl2br(htmlspecialchars($message)) . "
                        </div>
                    </div>
                    <div class='footer'>
                        <p>This message was sent from the UN-ICT website contact form.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $headers = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: UN-ICT Website <noreply@un-ict.africa>',
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion()
        );

        $mail_sent = mail($to, $subject, $email_content, implode("\r\n", $headers));

        if ($mail_sent) {
            header("Location: index.html?status=success&message=" . urlencode("Thank you for your message. We will get back to you soon!"));
        } else {
            // Log the error but don't show it to the user
            error_log("Failed to send email notification for contact form submission from: " . $email);
            header("Location: index.html?status=success&message=" . urlencode("Thank you for your message. We will get back to you soon!"));
        }
    } catch(PDOException $e) {
        // Log error
        error_log("Database error: " . $e->getMessage());
        // Redirect back with error message
        header("Location: index.html?status=error&message=" . urlencode("There was an error sending your message. Please try again later."));
    }
    exit;
} else {
    // If someone tries to access this file directly
    header("Location: index.html");
    exit;
}
?> 