<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Handle message deletion
if (isset($_POST['delete_message'])) {
    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->execute([$message_id]);
    } catch (PDOException $e) {
        error_log("Error deleting message: " . $e->getMessage());
    }
}

// Handle marking message as read
if (isset($_POST['mark_read'])) {
    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$message_id]);
    } catch (PDOException $e) {
        error_log("Error marking message as read: " . $e->getMessage());
    }
}

// Fetch messages
try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY date DESC");
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching messages: " . $e->getMessage());
    $messages = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UN-ICT</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>UN-ICT Admin</h2>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Contact Form Messages</h1>
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
            
            <div class="messages-table">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr class="<?php echo $message['is_read'] ? 'read' : 'unread'; ?>">
                                <td><?php echo date('Y-m-d H:i', strtotime($message['date'])); ?></td>
                                <td><?php echo htmlspecialchars($message['name']); ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td>
                                    <?php if ($message['is_read']): ?>
                                        <span class="status read">Read</span>
                                    <?php else: ?>
                                        <span class="status unread">New</span>
                                    <?php endif; ?>
                                </td>
                                <td class="message-actions">
                                    <button class="action-button view-button" onclick="viewMessage(<?php echo $message['id']; ?>)">
                                        View
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                        <button type="submit" name="delete_message" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this message?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Message Details</h2>
                <button class="close-button" onclick="closeModal()">&times;</button>
            </div>
            <div class="message-details" id="messageDetails">
                <!-- Message details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function viewMessage(messageId) {
            fetch(`get_message.php?id=${messageId}`)
                .then(response => response.json())
                .then(data => {
                    const details = document.getElementById('messageDetails');
                    details.innerHTML = `
                        <p><strong>Date:</strong> ${data.date}</p>
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Message:</strong></p>
                        <p>${data.message}</p>
                    `;
                    document.getElementById('messageModal').style.display = 'flex';
                    
                    // Mark as read
                    fetch('mark_read.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `message_id=${messageId}`
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html> 