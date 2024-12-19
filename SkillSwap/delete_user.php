<?php
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"));
$userId = $data->user_id;

$query = "DELETE FROM users WHERE user_id='$userId'";
$conn->query($query);

echo json_encode(['status' => 'success']);
?>
