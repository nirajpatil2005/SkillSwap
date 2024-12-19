<?php
// fetch_notifications.php

header('Content-Type: application/json');
include 'db_connect.php'; // Include your database connection

$user_id = intval($_GET['user_id']);

// Fetch notifications for the user
$query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);

$stmt->close();
$conn->close();

?>
