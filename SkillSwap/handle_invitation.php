<?php
session_start();
require_once 'db_connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = $_POST['notification_id'];
    $sender_id = $_POST['sender_id'];
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];

    if ($action == 'accept') {
        // Handle acceptance logic
        // Example: Add the user to the team in the `team_members` table
        $query = "INSERT INTO team_members (team_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $team_id, $user_id); // You can fetch the team_id from the projects table using sender_id
        $stmt->execute();

        // Mark notification as read
        $query = "UPDATE notifications SET is_read = 1 WHERE notification_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $notification_id);
        $stmt->execute();
    } elseif ($action == 'decline') {
        // Handle decline logic (just mark as read for now)
        $query = "UPDATE notifications SET is_read = 1 WHERE notification_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $notification_id);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    header('Location: notifications.php');
    exit;
}
?>
