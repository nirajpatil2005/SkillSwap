<?php
// Start the session
session_start();

// Include the database connection file
include('db_connect.php');

// Fetch unique skills from the user_skills table
$skills_query = "SELECT DISTINCT skill FROM user_skills";
$skills_result = mysqli_query($conn, $skills_query);

// Initialize an array to store skills
$skills = [];
if (mysqli_num_rows($skills_result) > 0) {
    while ($row = mysqli_fetch_assoc($skills_result)) {
        $skills[] = $row['skill'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Teammate | SkillSwap</title>
    <link rel="stylesheet" href="find-teammate.css">
</head>
<body>
    <!-- Header Section -->
    <header>
    <div class="logo">
            <a href="home.php">
                <img src="logo2.png" alt="Skill Swap Logo">
            </a>
        </div>
        <nav>
            <ul>
                <a href="home.php">
                    <button class="Profile">Home</button>
                </a>
            </ul>
        </nav>
    </header>

    <!-- Main Section: Find a Teammate -->
    <section class="teammate-section">
        <div class="teammate-container">
            <h2>Find a Teammate by Skill</h2>
            <!-- Skill Selection -->
            <div class="skill-selection">
                <?php foreach ($skills as $skill): ?>
                    <button class="skill-btn" data-skill="<?php echo htmlspecialchars($skill); ?>">
                        <?php echo htmlspecialchars($skill); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Teammate Cards Section -->
            <div id="teammateList" class="teammate-list">
                <!-- This section will be populated based on the selected skill -->
            </div>
        </div>
    </section>

   <!-- Profile Modal Structure -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="profileDetails">
            <!-- Profile information will be dynamically inserted here -->
        </div>
    </div>
</div>


    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>
    </footer>

    <script src="find-teammate.js"></script>
</body>
</html>
