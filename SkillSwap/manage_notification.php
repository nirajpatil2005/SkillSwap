<?php
// Include database connection
include 'db_connect.php';

// Function to fetch all notifications
function fetchNotifications($conn) {
    // Select notifications and join with user_basic_info to get sender names
    $query = "SELECT n.*, ubi.first_name AS sender_first_name, ubi.last_name AS sender_last_name 
              FROM notifications n 
              LEFT JOIN user_auth ua ON n.sender_id = ua.user_id 
              LEFT JOIN user_basic_info ubi ON ua.user_id = ubi.user_id
              ORDER BY n.created_at DESC"; // Order notifications by creation date
    $result = mysqli_query($conn, $query);

    $notifications = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
    } else {
        echo "Error fetching notifications: " . mysqli_error($conn); // Show error if query fails
    }
    return $notifications;
}

// Delete a notification
if (isset($_POST['delete_notification'])) {
    $notification_id = $_POST['notification_id']; // Get the notification ID from the form submission
    $delete_query = "DELETE FROM notifications WHERE notification_id = ?";
    
    // Prepare statement to avoid SQL injection
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $notification_id); // "i" indicates that the parameter is an integer
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Optional: Redirect or show a success message
        header("Location: notifications.php"); // Redirect to refresh the page
        exit(); // Prevent further script execution
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Fetch all notifications to display in the table
$notifications = fetchNotifications($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notifications</title>
    <link rel="stylesheet" href="home.css"> <!-- Assuming you have a CSS file -->
    <style>
        /* Add custom styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }

        table {
            width: 100%; /* Make table full width */
            border-collapse: collapse;
            font-size: 1.2em; /* Larger font size */
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4588d1; /* Light blue header */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Zebra striping */
        }

        button {
            padding: 10px 15px;
            background-color: #4588d1;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="open.php">
            <img src="logo2.png" alt="Skill Swap Logo">
        </a>
    </div>
    <nav>
        <ul>
            <li>
                <a href="notifications.php" id="notificationBtn">
                    <button class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count">3</span>
                    </button>
                </a>
            </li>
            <a href="profile.php" id="profileModalBtn">
                <button class="Profile">Profile</button>
            </a>
            <a href="logout.php">
                <button class="Profile">Logout</button>
            </a>
        </ul>
    </nav>
</header>

<main>
    <section>
        <!-- Display all notifications in a table -->
        <h2>Your Notifications</h2>
        <table>
            <tr>
                <th>Notification ID</th>
                <th>Sender First Name</th>
                <th>Sender Last Name</th>
                <th>Type</th>
                <th>Message</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($notifications as $notification): ?>
                <tr>
                    <td><?php echo $notification['notification_id']; ?></td>
                    <td><?php echo $notification['sender_first_name']; ?></td>
                    <td><?php echo $notification['sender_last_name']; ?></td>
                    <td><?php echo $notification['type']; ?></td>
                    <td><?php echo $notification['message']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($notification['created_at'])); ?></td>
                    <td>
                        <!-- Delete Notification Button -->
                        <form method="POST" action="notifications.php" style="display:inline-block;">
                            <input type="hidden" name="notification_id" value="<?php echo $notification['notification_id']; ?>">
                            <button type="submit" name="delete_notification" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>
    </footer>
</main>

</body>
</html>
