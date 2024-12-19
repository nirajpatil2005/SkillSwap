<?php
// Include the database connection
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch user data from the correct table
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM user_basic_info WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();

// Assuming you have skills in the database, fetch them if necessary
// You can modify this according to your actual database structure
$skills = "N/A"; // Initialize if not fetched from database
// Uncomment and modify this part if you need to fetch skills
// $stmt_skills = $conn->prepare("SELECT skills FROM user_skills WHERE user_id = ?");
// $stmt_skills->bind_param("i", $user_id);
// $stmt_skills->execute();
// $stmt_skills->bind_result($skills);
// $stmt_skills->fetch();
// $stmt_skills->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap Dashboard</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <li>
                    <a href="notifications.php" id="notificationBtn">
                        <button class="notification">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">3</span>
                        </button>
                    </a>
                </li>

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

    <!-- Main Section -->
    <main>
        <section class="about-section">
            <div class="about-box">
                <h2>Welcome to SkillSwap</h2>
                <p>Your go-to platform for connecting talented students with dynamic teams for competitions and
                    hackathons! Our mission is to empower students by making it easy for them to find the right
                    teammates and collaborate on exciting projects.</p>
                <a href="about.html" class="learn-more-btn">Learn More</a>
            </div>
        </section>

        <!-- Feature Cards -->
        <section class="feature-cards">
            <!-- Add feature cards here -->
            <div class="card">
                <h3>Find a Teammate</h3>
                <p>Search for skilled teammates and build a strong team to work on your next big idea.</p>
                <a href="find-teammate.php" class="card-btn">Explore</a>
            </div>
            <div class="card">
                <h3>Browse Projects</h3>
                <p>Looking for a team to join? Browse projects and join a team that matches your skills.</p>
                <a href="browse-projects.php" class="card-btn">Join Now</a>
            </div>
            <div class="card">
                <h3>Post a Project</h3>
                <p>Got a project idea? Post it here and find the best teammates to make it a reality.</p>
                <a href="post-project.php" class="card-btn">Post Project</a>
            </div>
            <div class="card">
                <h3>Hackathon Updates</h3>
                <p>Stay up-to-date with the latest hackathons, challenges, and competitions in your area.</p>
                <a href="hackathon-updates.html" class="card-btn">See Updates</a>
            </div>
        </section>

        <!-- Useful Section -->
        <section class="useful-container">
            <!-- Add useful section here -->
        </section>
    </main>

    <!-- Side Modal for Profile -->
    <div id="profileModal" class="profile-modal">
        <div class="profile-modal-content">
            <span class="close">&times;</span>
            <h2>Your Profile</h2>
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo $first_name . ' ' . $last_name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Skills:</strong> <?php echo $skills; ?></p>
                <button id="editProfileBtn">Edit Profile</button>
            </div>

            <!-- Edit Profile Form (Initially Hidden) -->
            <form id="editProfileForm" style="display: none;">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $first_name . ' ' . $last_name; ?>">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">

                <label for="skills">Skills:</label>
                <input type="text" id="skills" name="skills" value="<?php echo $skills; ?>">

                <label for="bio">Short Bio:</label>
                <textarea id="bio" name="bio" rows="4" placeholder="Tell us something about yourself"></textarea><br>

                <!-- File upload for CV -->
                <label for="cvUpload">Upload CV:</label>
                <input type="file" id="cvUpload" name="cvUpload" accept=".pdf,.doc,.docx">

                <!-- File upload for Projects -->
                <label for="projectUpload">Upload Projects:</label>
                <input type="file" id="projectUpload" name="projectUpload" accept=".zip,.rar,.7z"><br><br>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>

    </footer>

    <!-- Link to the JS file -->
    <script src="modal.js"></script>

</body>

</html>
