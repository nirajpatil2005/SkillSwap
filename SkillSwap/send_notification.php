<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false];

try {
    // Assume you have database connection code here

    // Insert notification logic
    $user_id = $_POST['user_id'];
    $sender_id = $_POST['sender_id'];
    $type = $_POST['type'];
    $message = $_POST['message'];

    // Example SQL Insert
    $sql = "INSERT INTO notifications (user_id, sender_id, type, message, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())";
    // Prepare and execute your statement here...

    // If the insert was successful
    $response['success'] = true;

} catch (Exception $e) {
    // Log the error message for debugging
    error_log($e->getMessage());
    $response['error'] = $e->getMessage();
}

// Return the JSON response
echo json_encode($response);
?>
