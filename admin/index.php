<?php
session_start();
require_once '../config/database.php';

// Basic authentication
$admin_username = "admin";
$admin_password = "your_secure_password"; // Change this to a secure password

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $error = "Invalid credentials";
        }
    }
    
    if (!isset($_SESSION['admin_logged_in'])) {
        // Show login form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login - UN-ICT</title>
            <link rel="stylesheet" href="../css/style.css">
            <style>
                .login-container {
                    max-width: 400px;
                    margin: 100px auto;
                    padding: 20px;
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .login-form input {
                    width: 100%;
                    padding: 10px;
                    margin: 10px 0;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .login-form button {
                    width: 100%;
                    padding: 10px;
                    background: var(--primary-color);
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .error {
                    color: red;
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <h2>Admin Login</h2>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form class="login-form" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Fetch messages
try {
    $stmt = $conn->query("SELECT * FROM messages ORDER BY date DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error fetching messages: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - UN-ICT</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .message-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .message-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .message-item:last-child {
            border-bottom: none;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .message-name {
            font-weight: bold;
            color: var(--primary-color);
        }
        .message-date {
            color: #666;
            font-size: 0.9em;
        }
        .message-email {
            color: #666;
            margin-bottom: 10px;
        }
        .message-content {
            white-space: pre-wrap;
        }
        .logout-btn {
            float: right;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Contact Form Messages</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php else: ?>
            <div class="message-list">
                <?php foreach ($messages as $message): ?>
                    <div class="message-item">
                        <div class="message-header">
                            <span class="message-name"><?php echo htmlspecialchars($message['name']); ?></span>
                            <span class="message-date"><?php echo date('F j, Y, g:i a', strtotime($message['date'])); ?></span>
                        </div>
                        <div class="message-email"><?php echo htmlspecialchars($message['email']); ?></div>
                        <div class="message-content"><?php echo htmlspecialchars($message['message']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 