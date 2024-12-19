<?php
session_start();
include('db_connect.php');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];

// Fetch the current user (team leader) from session
$sender_id = $_SESSION['user_id']; // Assuming session contains the team leader's user_id

// Check if the user_id and sender_id are valid
if (!isset($user_id) || !isset($sender_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid user or session']);
    exit;
}

// Prepare the notification entry
$type = 'team_invitation';
$message = 'You have been invited to join a team.';
$is_read = 0; // Mark notification as unread
$created_at = date('Y-m-d H:i:s');

// Insert into notifications table
$stmt = $conn->prepare("INSERT INTO notifications (user_id, sender_id, type, message, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('iissis', $user_id, $sender_id, $type, $message, $is_read, $created_at);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Invitation sent and notification created.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send invitation.']);
}

$stmt->close();
$conn->close();
?>
