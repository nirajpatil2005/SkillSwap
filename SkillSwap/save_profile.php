<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

// Include the database connection
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $bio = $_POST['bio'];
    $birthdate = $_POST['birthdate'];
    $mobile = $_POST['mobile'];

    // Check if user profile already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user_profile WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // If profile exists, update it
        $stmt = $conn->prepare("UPDATE user_profile SET first_name = ?, last_name = ?, bio = ?, birthdate = ?, mobile = ? WHERE user_id = ?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $bio, $birthdate, $mobile, $user_id);
    } else {
        // If profile doesn't exist, insert new profile
        $stmt = $conn->prepare("INSERT INTO user_profile (user_id, first_name, last_name, bio, birthdate, mobile) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $first_name, $last_name, $bio, $birthdate, $mobile);
    }
    
    if ($stmt->execute()) {
        // Redirect to profile completion success page
        header("Location: profile_complete_success.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
