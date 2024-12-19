<?php
// Include database connection
include 'db_connect.php';

// Function to fetch all projects
function fetchProjects($conn) {
    // Select from projects and join with user_basic_info to get first and last names
    $query = "SELECT p.*, ubi.first_name, ubi.last_name, ubi.username 
              FROM projects p 
              LEFT JOIN user_auth ua ON p.leader_id = ua.user_id 
              LEFT JOIN user_basic_info ubi ON ua.user_id = ubi.user_id";
    $result = mysqli_query($conn, $query);

    $projects = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $projects[] = $row;
        }
    } else {
        echo "Error fetching projects: " . mysqli_error($conn); // Show error if query fails
    }
    return $projects;
}

// Delete a project
if (isset($_POST['delete_project'])) {
    $project_id = $_POST['project_id']; // Get the project ID from the form submission
    $delete_query = "DELETE FROM projects WHERE project_id = ?";
    
    // Prepare statement to avoid SQL injection
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $project_id); // "i" indicates that the parameter is an integer
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Optional: Redirect or show a success message
        header("Location: manage_projects.php"); // Redirect to refresh the page
        exit(); // Prevent further script execution
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Fetch all projects to display in the table
$projects = fetchProjects($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <link rel="stylesheet" href="home.css"> <!-- Assuming you have a CSS file -->
    <style>
        /* Add custom styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }

        table {
            width: 100%; /* Make table full width */
            border-collapse: collapse;
            font-size: 1.2em; /* Larger font size */
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4588d1; /* Light blue header */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Zebra striping */
        }

        button {
            padding: 10px 15px;
            background-color: #4588d1;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>

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
            <a href="profile.php" id="profileModalBtn">
                <button class="Profile">Profile</button>
            </a>
            <a href="logout.php">
                <button class="Profile">Logout</button>
            </a>
        </ul>
    </nav>
</header>

<main>
    <section>
        <!-- Display all projects in a table -->
        <h2>Existing Projects</h2>
        <table>
            <tr>
                <th>Project ID</th>
                <th>Project Name</th>
                <th>Project Description</th>
                <th>Leader First Name</th>
                <th>Leader Last Name</th>
                <th>Leader Username</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo $project['project_id']; ?></td>
                    <td><?php echo $project['project_name']; ?></td>
                    <td><?php echo $project['project_description']; ?></td>
                    <td><?php echo $project['first_name']; ?></td>
                    <td><?php echo $project['last_name']; ?></td>
                    <td><?php echo $project['username']; ?></td>
                    <td>
                        <!-- Update Project Form -->
                        <form method="POST" action="manage_projects.php" style="display:inline-block;">
                            <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                            <input type="text" name="project_name" value="<?php echo $project['project_name']; ?>" required>
                            <textarea name="project_description" required><?php echo $project['project_description']; ?></textarea>
                            <button type="submit" name="update_project">Update</button>
                        </form>

                        <!-- Delete Project Button -->
                        <form method="POST" action="manage_projects.php" style="display:inline-block;">
                            <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                            <button type="submit" name="delete_project" onclick="return confirm('Are you sure you want to delete this project?')">Delete Project</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>
    </footer>
</main>

</body>
</html>
