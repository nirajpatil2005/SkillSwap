<?php
session_start();
require_once 'db_connect.php'; // Include the database connection file

// Function to escape HTML for output
function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

try {
    // Check if user session is set
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User session not set.");
    }
    
    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Query to fetch notifications for the logged-in user
    // Added join to the projects table to get project names
    $sql = "SELECT n.notification_id, n.sender_id, n.type, n.created_at, 
                   u.first_name, u.last_name, p.project_name, n.message
            FROM notifications n
            INNER JOIN user_basic_info u ON n.sender_id = u.user_id
            LEFT JOIN projects p ON n.project_id = p.project_id
            WHERE n.user_id = ?"; // Only fetching notifications for this user
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id); // Bind user_id as recipient
    
    // Execute query
    if (!$stmt->execute()) {
        throw new Exception("SQL Execute Error: " . $stmt->error);
    }
    
    // Get result set
    $result = $stmt->get_result();
    
    // Fetch notifications as associative array
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    
} catch (Exception $e) {
    // Handle exceptions
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap - Notifications</title>
    <link rel="stylesheet" href="notifications.css"> <!-- Reuse your homepage CSS for consistent styling -->
</head>
<body>
    
    <!-- Header Section (Reuse from Homepage) -->
    <header>
        <div class="logo">
            <img src="logo2.png" alt="SkillSwap Logo">
        </div>
        <nav>
            <ul>
                <li>
                    <a href="notifications.php" id="notificationBtn">
                        <button class="notification">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count"><?php echo count($notifications); ?></span>
                        </button>
                    </a>
                </li>
                <li>
                    <a href="home.php">
                        <button class="Profile">Home</button>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Notification Section -->
    <main>
        <section class="notification-section">
            <div class="notification-box">
                <h2>Your Notifications</h2>

                <?php if (empty($notifications)): ?>
                    <p>No notifications found.</p>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item">
                        <h3><?php echo escape($notification['type']); ?></h3>
                        <p>
                            <strong><?php echo escape($notification['first_name'] . ' ' . $notification['last_name']); ?></strong> 
                            <?php 
                            // Only show message for notifications that are NOT of type 'join_team'
                            if ($notification['type'] === 'join_team'): 
                            ?>
                                Project: <strong><?php echo escape($notification['project_name']); ?></strong>
                            <?php else: ?>
                                <?php if (isset($notification['message'])): ?>
                                    <?php echo escape($notification['message']); ?>.
                                <?php else: ?>
                                    <!-- Handle case where message key is not set -->
                                    <em>No message available.</em>
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                        
                        <!-- Accept and Decline Buttons -->
                        <button class="accept-btn" onclick="handleAccept(<?php echo $notification['notification_id']; ?>)">Accept</button>
                        <button class="decline-btn" onclick="handleDecline(<?php echo $notification['notification_id']; ?>)">Decline</button>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Additional content or history can be added here if needed -->
            </div>
        </section>
    </main>

    <!-- Footer Section (Reuse from Homepage) -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil</p>
    </footer>

    <!-- Link to Font Awesome for Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        // Function to handle Accept action
        function handleAccept(notificationId) {
            // Implement your accept logic here, e.g., AJAX call to update the database
            console.log("Accepted notification ID:", notificationId);
        }

        // Function to handle Decline action
        function handleDecline(notificationId) {
            // Implement your decline logic here, e.g., AJAX call to update the database
            console.log("Declined notification ID:", notificationId);
        }
    </script>
</body>
</html>
