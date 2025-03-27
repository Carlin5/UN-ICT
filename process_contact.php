<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $date = date('Y-m-d H:i:s');

    $to = "legal@un-ict.africa";
    $subject = "New Contact Form Submission from UN-ICT Website";
    
    // Create email content
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

    // Email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: ' . $email,
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion()
    );

    // Send email
    if (mail($to, $subject, $email_content, implode("\r\n", $headers))) {
        // Redirect back with success message
        header("Location: index.html?status=success&message=Thank you for your message. We will get back to you soon!");
    } else {
        // Redirect back with error message
        header("Location: index.html?status=error&message=There was an error sending your message. Please try again later.");
    }
    exit;
} else {
    // If someone tries to access this file directly
    header("Location: index.html");
    exit;
}
?> 