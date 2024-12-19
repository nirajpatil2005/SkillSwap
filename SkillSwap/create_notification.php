<?php
// create_notification.php

// Database connection
include 'db_connect.php'; // Make sure this file contains your database connection code

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id'], $data['sender_id'], $data['type'], $data['message'], $data['is_read'], $data['created_at'])) {
    $userId = $data['user_id'];
    $senderId = $data['sender_id'];
    $type = $data['type'];
    $message = $data['message'];
    $isRead = $data['is_read'];
    $createdAt = $data['created_at'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, sender_id, type, message, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissis", $userId, $senderId, $type, $message, $isRead, $createdAt);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
}

$conn->close();
?>
