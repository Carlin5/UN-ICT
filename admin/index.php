<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle message deletion
if (isset($_POST['delete_message'])) {
    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->bindParam(':id', $message_id);
        $stmt->execute();
    } catch(PDOException $e) {
        error_log("Error deleting message: " . $e->getMessage());
    }
    header("Location: index.php");
    exit;
}

// Handle mark as read
if (isset($_POST['mark_read'])) {
    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("UPDATE messages SET is_read = TRUE WHERE id = :id");
        $stmt->bindParam(':id', $message_id);
        $stmt->execute();
    } catch(PDOException $e) {
        error_log("Error marking message as read: " . $e->getMessage());
    }
    header("Location: index.php");
    exit;
}

// Get all messages
try {
    $stmt = $conn->query("SELECT * FROM messages ORDER BY date DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Error fetching messages: " . $e->getMessage());
    $messages = [];
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
            margin: 80px auto 20px;
            padding: 20px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .message-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .message-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-content {
            flex: 1;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .message-date {
            color: #666;
            font-size: 0.9em;
        }

        .message-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .unread {
            background-color: #f8f9fa;
        }

        .no-messages {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        .logout-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Contact Form Messages</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if (empty($messages)): ?>
            <div class="no-messages">No messages yet</div>
        <?php else: ?>
            <div class="message-list">
                <?php foreach ($messages as $message): ?>
                    <div class="message-item <?php echo $message['is_read'] ? '' : 'unread'; ?>">
                        <div class="message-content">
                            <div class="message-header">
                                <h3><?php echo htmlspecialchars($message['name']); ?></h3>
                                <span class="message-date"><?php echo date('Y-m-d H:i:s', strtotime($message['date'])); ?></span>
                            </div>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                            <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                        <div class="message-actions">
                            <?php if (!$message['is_read']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" name="mark_read" class="btn btn-primary">Mark as Read</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                <button type="submit" name="delete_message" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 