<?php
// Include database connection
// Example of db_connect.php
$conn = new mysqli("localhost", "root", "Niraj@$123", "skills_swap");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log('form submitted');
    // Check if required POST variables are set
    if (isset($_POST['projectTitle'], $_POST['projectDescription'], $_POST['category'], $_POST['skillsRequired'], $_POST['deadline'])) {
        // Retrieve project details from the form
        $project_title = $_POST['projectTitle'];
        $project_description = $_POST['projectDescription'];
        $category = $_POST['category'];
        $skills_required = $_POST['skillsRequired'];
        $deadline = $_POST['deadline'];

        // SQL query to insert project data into the 'projects' table
        $stmt = $conn->prepare("INSERT INTO projects (project_name, project_description, project_category, skills_required, end_date, leader_id) 
            VALUES (?, ?, ?, ?, ?, ?)");

        // Check if prepare was successful
        if ($stmt) {
            echo"hi";
            $stmt->bind_param("sssssi", $project_title, $project_description, $category, $skills_required, $deadline, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Project posted successfully!'); window.location.href = 'form-team.html';</script>";
            } else {
                echo "<script>alert('Error posting the project: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing the statement: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Project | SkillSwap</title>
    <link rel="stylesheet" href="post-project.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <img src="logo2.png" alt="SkillSwap Logo">
        </div>
        <nav>
            <ul>
                <a href="home.php">
                    <button class="Profile">Home</button>
                </a>
            </ul>
        </nav>
    </header>

    <!-- Main Section: Post a Project Form -->
    <section class="project-section">
        <div class="project-container">
            <div class="project-header">
                <h2>Post Your Project</h2>
                <p>Fill out the details about your project below and share it with the SkillSwap community!</p>
            </div>

            <!-- Project Form -->
            <form id="postProjectForm" action="post_project.php" method="POST">
                <label for="project-title">Project Title:</label>
                <input type="text" id="project-title" name="projectTitle" required>

                <label for="project-description">Project Description:</label>
                <textarea id="project-description" name="projectDescription" rows="4" required></textarea>

                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Hardware">Hardware</option>
                    <option value="Software">Software</option>
                </select>

                <label for="skills-required">Skills Required:</label>
                <input type="text" id="skills-required" name="skillsRequired" required>

                <label for="deadline">Deadline:</label>
                <input type="date" id="deadline" name="deadline" required>
                <label for="post project">post project</label>
                <input type="submit"id="post-project" name="post-project" required>Post Project
            </form>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil</p>
    </footer>

    <script src="post-project.js"></script>
</body>
</html>