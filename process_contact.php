<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $date = date('Y-m-d H:i:s');

    try {
        // Email configuration
        $to = "legal@un-ict.africa";
        $subject = "New Contact Form Submission from UN-ICT Website";
        
        // Create HTML email content
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
            echo json_encode([
                'success' => true, 
                'message' => 'Thank you for your message. We will get back to you soon!'
            ]);
        } else {
            throw new Exception('Failed to send email');
        }

    } catch(Exception $e) {
        error_log('Contact form error: ' . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'There was an error sending your message. Please try again later.'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false, 
        'message' => 'Method not allowed'
    ]);
}
?> 