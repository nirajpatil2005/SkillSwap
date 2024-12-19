<?php
// Start the session
session_start();

// Include the database connection file
include('db_connect.php');

// Check if project_id and user_id are received via POST
if (isset($_POST['project_id']) && isset($_POST['user_id'])) {
    $projectId = $_POST['project_id'];
    $userId = $_POST['user_id'];

    // Get the team leader's user_id from the projects table
    $sql = "SELECT leader_id FROM projects WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $leaderId = $row['leader_id'];

        // Insert notification into notifications table
        $notificationType = "Team Invitation";
        $notificationMessage = "You have received a team invitation for Project ID: $projectId.";
        $createdAt = date('Y-m-d H:i:s');

        $insertSql = "INSERT INTO notifications (user_id, sender_id, type, message, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertSql);
        $stmtInsert->bind_param("iisss", $leaderId, $userId, $notificationType, $notificationMessage, $createdAt);
        $stmtInsert->execute();

        if ($stmtInsert->affected_rows > 0) {
            // Success response
            echo "Notification sent successfully!";
        } else {
            // Error response
            echo "Failed to send notification.";
        }
    } else {
        // Project not found error
        echo "Project not found.";
    }
} else {
    // Invalid request error
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
