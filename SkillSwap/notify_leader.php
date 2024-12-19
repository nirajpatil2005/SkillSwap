<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the database connection file
    require_once 'db_connect.php';

    // Retrieve data from POST request
    $projectId = $_POST['project_id'];
    $userId = $_POST['user_id'];
    $leaderId = $_POST['leader_id'];

    // Prepare and execute SQL query to insert notification
    $type = 'join_team';
    $message = "";  // Set the message to an empty string or your desired message
    $isRead = 0;

    // Check for duplicate notification
    $checkQuery = "SELECT * FROM notifications WHERE user_id = ? AND sender_id = ? AND project_id = ? AND type = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "iiis", $leaderId, $userId, $projectId, $type);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        // Duplicate entry found
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => 'Request already sent.']);
        exit;
    }

    // Insert the notification
    $sql = "INSERT INTO notifications (user_id, sender_id, type, message, is_read, created_at, project_id)
            VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisiii", $leaderId, $userId, $type, $message, $isRead, $projectId);

    if (mysqli_stmt_execute($stmt)) {
        http_response_code(200); // Success
        echo json_encode(['success' => true, 'message' => 'Notification sent successfully.']);
    } else {
        http_response_code(500); // Server error
        echo json_encode(['success' => false, 'message' => 'Failed to send notification.']);
    }

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
