<?php
// Include the database connection
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT user_id, password FROM user_auth WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Get user basic info
            $stmt_basic = $conn->prepare("SELECT first_name, last_name FROM user_basic_info WHERE user_id = ?");
            $stmt_basic->bind_param("i", $user_id);
            $stmt_basic->execute();
            $stmt_basic->bind_result($first_name, $last_name);
            $stmt_basic->fetch();
            
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['full_name'] = $first_name . ' ' . $last_name;

            // Redirect to home.php after successful sign-in
            echo "<script>
                alert('Sign in successful! Redirecting to home page...');
                window.location.href = 'home.php';
            </script>";
        } else {
            echo "<script>
                alert('Incorrect password!');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Email not found. Please sign up.');
            window.history.back();
        </script>";
    }

    $stmt->close();
    if (isset($stmt_basic)) $stmt_basic->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="sign_in.css">
</head>
<body>
    <div class="video-background">
        <video autoplay muted loop>
            <source src="background-video.mp4" type="video/mp4">
        </video>
    </div>
    <header>
        <div class="logo">
            <a href="open.php">
                <img src="logo.png" alt="Skill Swap Logo">
            </a>
        </div>
    </header>
    <main>
        <div class="signin-container">
            <h2>Sign in</h2>
            <form action="sign_in.php" method="POST">
                <div class="input-container">
                    <label for="email">Email ID</label>
                    <input type="email" id="email" name="email" placeholder="Enter your Email ID" required>
                </div>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your Password" required>
                </div>
                <button type="submit" class="signin-button">Sign in</button>
            </form>
            <p>Forgot password? <a href="reset_password.html">Reset password</a></p>
            <p>New to Skill Swap? <a href="sign_up.php">Sign up</a></p>
        </div>
    </main>
</body>
</html>
