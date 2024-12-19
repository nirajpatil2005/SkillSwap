<?php
// Start the session
session_start();

// Include the database connection file
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from POST request
    $projectId = mysqli_real_escape_string($conn, $_POST['project_id']);
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Insert notification into notifications table
    $notificationType = 'join_team';
    $message = "You have a new team member request for Project ID: $projectId";
    $createdAt = date('Y-m-d H:i:s');

    $insertSql = "
        INSERT INTO notifications (user_id, sender_id, type, message, created_at)
        VALUES ('$userId', (SELECT leader_id FROM projects WHERE project_id = '$projectId'), '$notificationType', '$message', '$createdAt')
    ";

    if (mysqli_query($conn, $insertSql)) {
        echo 'Notification sent successfully.';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
