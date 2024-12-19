<?php
// Start the session
session_start();

// Include the database connection file
include('db_connect.php');

// Fetch projects and their team leaders' names from the database
$sql = "
    SELECT p.project_id, p.project_name, p.project_description, p.project_category, u.first_name, u.last_name, p.leader_id
    FROM projects p
    JOIN user_basic_info u ON p.leader_id = u.user_id
";

$result = mysqli_query($conn, $sql);

// Check if any projects exist
$projects = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Projects | SkillSwap</title>
    <link rel="stylesheet" href="browse-projects.css">
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

    <!-- Main Section: Browse Projects -->
    <section class="projects-section">
        <div class="projects-container">
            <h2>Browse Projects</h2>
            <!-- Category Selection -->
            <div class="category-selection">
                <button id="showHardware" class="category-btn">View Hardware Projects</button>
                <button id="showSoftware" class="category-btn">View Software Projects</button>
            </div>
            
            <!-- Projects List -->
            <div id="projectsList" class="projects-list">
                <!-- Hardware Projects Section (initially hidden) -->
                <div id="hardwareProjects" class="project-category" style="display: none;">
                    <h3>Hardware Projects</h3>
                    <div class="project-cards">
                        <?php foreach ($projects as $project): ?>
                            <?php if (strtolower($project['project_category']) === 'hardware'): ?>
                                <div class="project-card">
                                    <h4><?php echo htmlspecialchars($project['project_name']); ?></h4>
                                    <p><strong>Project ID:</strong> <?php echo htmlspecialchars($project['project_id']); ?></p>
                                    <p><strong>Team Leader:</strong> <?php echo htmlspecialchars($project['first_name'] . ' ' . $project['last_name']); ?></p>
                                    <p><?php echo htmlspecialchars($project['project_description']); ?></p>
                                    <input type="hidden" class="leader-id" value="<?php echo htmlspecialchars($project['leader_id']); ?>">
                                    <button class="join-btn" data-project-id="<?php echo htmlspecialchars($project['project_id']); ?>">Join Team</button>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Software Projects Section (initially hidden) -->
                <div id="softwareProjects" class="project-category" style="display: none;">
                    <h3>Software Projects</h3>
                    <div class="project-cards">
                        <?php foreach ($projects as $project): ?>
                            <?php if (strtolower($project['project_category']) === 'software'): ?>
                                <div class="project-card">
                                    <h4><?php echo htmlspecialchars($project['project_name']); ?></h4>
                                    <p><strong>Project ID:</strong> <?php echo htmlspecialchars($project['project_id']); ?></p>
                                    <p><strong>Team Leader:</strong> <?php echo htmlspecialchars($project['first_name'] . ' ' . $project['last_name']); ?></p>
                                    <p><?php echo htmlspecialchars($project['project_description']); ?></p>
                                    <input type="hidden" class="leader-id" value="<?php echo htmlspecialchars($project['leader_id']); ?>">
                                    <button class="join-btn" data-project-id="<?php echo htmlspecialchars($project['project_id']); ?>">Join Team</button>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>
    </footer>

    <!-- JavaScript for Project Category Display -->
    <script src="browse-projects.js"></script>

    <!-- JavaScript for Join Button Click Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const showHardwareBtn = document.getElementById('showHardware');
            const showSoftwareBtn = document.getElementById('showSoftware');
            const hardwareProjects = document.getElementById('hardwareProjects');
            const softwareProjects = document.getElementById('softwareProjects');

            // Show Hardware Projects
            showHardwareBtn.addEventListener('click', () => {
                hardwareProjects.style.display = 'block';
                softwareProjects.style.display = 'none';
            });

            // Show Software Projects
            showSoftwareBtn.addEventListener('click', () => {
                softwareProjects.style.display = 'block';
                hardwareProjects.style.display = 'none';
            });

            // Join Team Button Click Handler
            const joinButtons = document.querySelectorAll('.join-btn');
            joinButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const projectId = button.getAttribute('data-project-id');
                    const userId = <?php echo json_encode($_SESSION['user_id']); ?>; // Get user_id from PHP session
                    const leaderId = button.parentElement.querySelector('.leader-id').value; // Get leader_id from hidden input

                    // AJAX Request to notify team leader
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'notify_leader.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            alert('Notification sent to team leader!');
                            // Optionally, update UI to indicate notification sent
                        } else {
                            alert('Failed to send notification to team leader.');
                        }
                    };
                    xhr.onerror = function() {
                        alert('Error: Unable to process your request.');
                    };
                    xhr.send(`project_id=${projectId}&user_id=${userId}&leader_id=${leaderId}`);
                });
            });
        });
    </script>
</body>
</html>
