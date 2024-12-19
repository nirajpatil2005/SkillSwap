<?php
// Include the database connection
include 'db_connect.php';
session_start();

// Check if the user is logged in as admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
//     header("Location: sign_in.php");
//     exit();
// }

// $user_id = $_SESSION['user_id'];
// Fetch admin data from the correct table
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM user_basic_info WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <!-- Header Section -->
    <header>
    <div class="logo">
            <a href="open.php">
                <img src="logo2.png" alt="Skill Swap Logo">
            </a>
        </div>
        <nav>
            <ul>
            <!-- <li>
                    <a href="notifications.php" id="notificationBtn">
                        <button class="notification">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">3</span>
                        </button>
                    </a>
                </li> -->

            <!-- <a href="notifications.html" id="notificationBtn">
                    <button class="notification">
                        <i class="fas fa-bell"></i>
                    </button>
                </a> -->
                <a href="profile.php" id="profileModalBtn">
                    <button class="Profile">Profile</button>
                </a>
                <a href="logout.php">
                    <button class="Profile">Logout</button>
                </a>
            </ul>
        </nav>
    </header>

    <!-- About Section (Admin Welcome Message) -->
    <section class="about-section">
        <div class="about-box">
            <h2>Welcome, Admin</h2>
            <p>Manage users, projects, and oversee platform activity. Use the tools below to ensure smooth operations.</p>
            <a href="#manage-users" class="learn-more-btn">Get Started</a>
        </div>
    </section>

    <!-- Feature Cards (Admin Tools) -->
    <section class="feature-cards">
        <div class="card">
            <h3>Manage Users</h3>
            <p>View, edit, and remove users registered on the platform.</p>
            <a href="manage_users.php" class="card-btn">Manage</a>
        </div>

        <div class="card">
            <h3>Manage Projects</h3>
            <p>Oversee ongoing projects and assign admins to monitor progress.</p>
            <a href="manage_projects.php" class="card-btn">View Projects</a>
        </div>

        <div class="card">
            <h3>Notifications</h3>
            <p>Check platform-wide notifications and important updates.</p>
            <a href="manage_notification.php" class="card-btn">View Notifications</a>
        </div>

        <div class="card">
            <h3>Hackathons</h3>
            <p>Update hackathon details and inform users of new opportunities.</p>
            <a href="manage_hackathons.php" class="card-btn">Manage Hackathons</a>
        </div>
    </section>

    <!-- Useful Info Section (Admin Notes or Quick Links) -->
    <section class="useful-container">
        <h2>Useful Resources</h2>
        <p>Access platform guidelines, admin resources, and FAQs for better management.</p>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 SkillSwap. All rights reserved.</p>
        <a href="#">Terms of Service</a> | <a href="#">Privacy Policy</a>
    </footer>

    <!-- Profile Modal (Same as User Side) -->
    <div class="profile-modal" id="profileModal">
        <div class="profile-modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Admin Profile</h2>
            <div class="profile-info">
                <p>Name: Admin Name</p>
                <p>Email: admin@example.com</p>
                <button id="editProfileBtn">Edit Profile</button>
            </div>
        </div>
    </div>

    <script src="ad_dashboard.js"></script>
</body>
</html>

