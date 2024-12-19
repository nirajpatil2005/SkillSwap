<?php
// Start the session
session_start();

// Include the database connection file
include('db_connect.php');

// Initialize variables for form data
$project_name = $project_description = $project_category = $skills_required = $end_date = "";
$leader_id = "";

// Check if the form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if all required fields are set
    if (isset($_POST['project-title'], $_POST['project-description'], $_POST['category'], $_POST['skills-required'], $_POST['deadline'])) {

        // Sanitize and escape user inputs
        $project_name = mysqli_real_escape_string($conn, $_POST['project-title']);
        $project_description = mysqli_real_escape_string($conn, $_POST['project-description']);
        $project_category = mysqli_real_escape_string($conn, $_POST['category']);
        $skills_required = mysqli_real_escape_string($conn, $_POST['skills-required']);
        $end_date = mysqli_real_escape_string($conn, $_POST['deadline']);

        // Get leader_id from session (assuming user_id is stored in the session after login)
        if (isset($_SESSION['user_id'])) {
            $leader_id = $_SESSION['user_id'];
        } else {
            echo "<script>alert('You must be logged in to post a project.'); window.location.href='sign_in.php';</script>";
            exit();
        }

        // Insert form data into the projects table
        $sql = "INSERT INTO projects (project_name, project_description, project_category, skills_required, end_date, leader_id) 
                VALUES ('$project_name', '$project_description', '$project_category', '$skills_required', '$end_date', '$leader_id')";

        if (mysqli_query($conn, $sql)) {
            // Success message after inserting the data and redirect to index.php
            echo "<script>alert('Project posted successfully!'); window.location.href='home.php';</script>";
            exit();
        } else {
            // Error message if something goes wrong
            echo "<script>alert('Error: Unable to post project. Please try again later.'); window.location.href='home.php';</script>";
            exit();
        }

    } else {
        // If required fields are not set, display an error
        echo "<script>alert('Please fill in all required fields.'); window.location.href='home.php';</script>";
        exit();
    }
}

// Close the database connection only if it's a valid resource
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Your Project</title>
    <link rel="stylesheet" href="post-project.css"> <!-- Link to external CSS file -->
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="logo2.png" alt="SkillSwap Logo">
    </div>
    <a href="home.php">
      <button class="Profile">Home</button>
    </a>
</header>
<section class="project-section">
    <div class="project-container">
        <div class="project-header">
             <h2>Post Your Project</h2>
            <p>Fill out the details about your project below and share it with SkillSwap Community!</p>
        </div>
    <form action="post-project.php" method="POST">
        <label for="project-title">Project Title:</label>
        <input type="text" id="project-title" name="project-title" required>

        <label for="project-description">Project Description:</label>
        <textarea id="project-description" name="project-description" rows="5" required></textarea>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Software">Software</option>
            <option value="Hardware">Hardware</option>            
        </select>

        <label for="skills-required">Skills Required:</label>
        <input type="text" id="skills-required" name="skills-required" required>

        <label for="deadline">Deadline:</label>
        <input type="date" id="deadline" name="deadline" required>

        <button type="submit">Post Project</button>
    </form>
</section>    
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>&copy; 2024 SkillSwap. All rights reserved.</p>
    </footer>
</div>

</body>
</html>
