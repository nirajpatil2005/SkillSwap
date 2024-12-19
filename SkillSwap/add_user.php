<?php
include 'db_connect.php';

$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$role = $_POST['role'];

$query = "INSERT INTO users (first_name, last_name, email, role) VALUES ('$firstName', '$lastName', '$email', '$role')";
$conn->query($query);

echo json_encode(['status' => 'success']);
?>
