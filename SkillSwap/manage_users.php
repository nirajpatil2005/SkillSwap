<?php
// Include database connection
include 'db_connect.php';

// Function to fetch all users
function fetchUsers($conn) {
    $query = "SELECT ua.*, ubi.first_name, ubi.last_name, ubi.username 
              FROM user_auth ua 
              LEFT JOIN user_basic_info ubi ON ua.user_id = ubi.user_id";
    $result = mysqli_query($conn, $query);

    $users = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    return $users;
}

// Create a new user
if (isset($_POST['create_user'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Use password hashing
    $sign_up_date = date('Y-m-d H:i:s'); // Capture current timestamp

    $query = "INSERT INTO user_auth (email, password, sign_up_date) VALUES ('$email', '$password', '$sign_up_date')";
    mysqli_query($conn, $query);
    header("Location: manage_users.php");
    exit;
}

// Update user details
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Use password hashing

    $query = "UPDATE user_auth SET email='$email', password='$password' WHERE user_id='$user_id'";
    mysqli_query($conn, $query);
    header("Location: manage_users.php");
    exit;
}

// Delete a user
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    $query = "DELETE FROM user_auth WHERE user_id='$user_id'";
    mysqli_query($conn, $query);
    header("Location: manage_users.php");
    exit;
}

// Fetch all users to display in table
$users = fetchUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        .hidden {
            display: none; /* Hide add user form by default */
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
        <!-- Toggle button for adding new user -->
        <h2>
            <button onclick="toggleAddUserForm()">Add New User</button>
        </h2>
        
        <!-- Hidden Form to create new user -->
        <div id="addUserForm" class="hidden">
            <form method="POST" action="manage_users.php">
                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" required>

                <button type="submit" name="create_user">Add User</button>
            </form>
        </div>
    </section>

    <section>
        <!-- Display all users in a table -->
        <h2>Existing Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Sign Up Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['first_name']; ?></td>
                    <td><?php echo $user['last_name']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['sign_up_date']; ?></td>
                    <td>
                        <!-- Update User Form -->
                        <form method="POST" action="manage_users.php" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                            <input type="password" name="password" placeholder="New Password">
                            <button type="submit" name="update_user">Update</button>
                        </form>

                        <!-- Delete User Button -->
                        <form method="POST" action="manage_users.php" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button type="submit" name="delete_user" onclick="return confirm('Are you sure?')">Delete User</button>
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

<script>
    function toggleAddUserForm() {
        var form = document.getElementById("addUserForm");
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
</script>

</body>
</html>
