<?php
// Include the database connection
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // Capture the confirm password

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Passwords do not match. Please try again.');
            window.history.back();
        </script>";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the user_auth table (email and password only)
    $stmt = $conn->prepare("INSERT INTO user_auth (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // Get the inserted user's ID
        $new_user_id = $stmt->insert_id;

        // Start a session and store the user's ID
        session_start();
        $_SESSION['user_id'] = $new_user_id;

        // Redirect the user to the profile completion page
        header("Location: profile4.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="sign_up.css">
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
        <div class="signup-container">
            <h2>Sign Up</h2>
            <form action="sign_up.php" method="POST">
                <div class="input-container">
                    <label for="email">Email ID</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email ID" required>
                </div>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-container">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="signup-button">Sign Up</button>
            </form>
            <p>Already have an account? <a href="sign_in.php">Sign in</a></p>
        </div>
    </main>
</body>
</html>
