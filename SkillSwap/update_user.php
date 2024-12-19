<?php
include 'db_connect.php';

$userId = $_POST['user_id'];
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$role = $_POST['role'];

$query = "UPDATE users SET first_name='$firstName', last_name='$lastName', email='$email', role='$role' WHERE user_id='$userId'";
$conn->query($query);

echo json_encode(['status' => 'success']);
?>
