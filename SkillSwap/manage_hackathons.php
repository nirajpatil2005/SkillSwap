<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db_connect.php';

// Function to fetch all hackathons
function fetchHackathons($conn) {
    $query = "SELECT * FROM hackathons ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    $hackathons = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $hackathons[] = $row;
        }
    } else {
        echo "Error fetching hackathons: " . mysqli_error($conn);
    }
    return $hackathons;
}

// Add a new hackathon
if (isset($_POST['add_hackathon'])) {
    $name = $_POST['hackathon_name'];
    $description = $_POST['hackathon_description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $insert_query = "INSERT INTO hackathons (hackathon_name, hackathon_description, start_date, end_date) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $insert_query)) {
        mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $start_date, $end_date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: manage_hackathons.php"); // Redirect to refresh the page
        exit();
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Delete a hackathon
if (isset($_POST['delete_hackathon'])) {
    $hackathon_id = $_POST['hackathon_id'];
    $delete_query = "DELETE FROM hackathons WHERE hackathon_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $hackathon_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: manage_hackathons.php");
        exit();
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Update a hackathon
if (isset($_POST['update_hackathon'])) {
    $hackathon_id = $_POST['hackathon_id'];
    $name = $_POST['hackathon_name'];
    $description = $_POST['hackathon_description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $update_query = "UPDATE hackathons SET hackathon_name = ?, hackathon_description = ?, start_date = ?, end_date = ? WHERE hackathon_id = ?";

    if ($stmt = mysqli_prepare($conn, $update_query)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $description, $start_date, $end_date, $hackathon_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: manage_hackathons.php");
        exit();
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Fetch all hackathons to display
$hackathons = fetchHackathons($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hackathon Management</title>
    <link rel="stylesheet" href="home.css"> <!-- Assuming you have a CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4588d1;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
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
            <li><a href="notifications.php">Notifications</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section>
        <h2>Manage Hackathons</h2>

        <!-- Form to Add Hackathon -->
        <h3>Add New Hackathon</h3>
        <form method="POST" action="manage_hackathons.php">
            <label for="hackathon_name">Hackathon Name:</label>
            <input type="text" id="hackathon_name" name="hackathon_name" required>
            <label for="hackathon_description">Description:</label>
            <textarea id="hackathon_description" name="hackathon_description" required></textarea>
            <label for="start_date">Start Date:</label>
            <input type="datetime-local" id="start_date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="datetime-local" id="end_date" name="end_date" required>
            <button type="submit" name="add_hackathon">Add Hackathon</button>
        </form>

        <!-- Display all hackathons in a table -->
        <h3>Existing Hackathons</h3>
        <table>
            <tr>
                <th>Hackathon ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($hackathons as $hackathon): ?>
                <tr>
                    <td><?php echo $hackathon['hackathon_id']; ?></td>
                    <td><?php echo $hackathon['hackathon_name']; ?></td>
                    <td><?php echo $hackathon['hackathon_description']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($hackathon['start_date'])); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($hackathon['end_date'])); ?></td>
                    <td>
                        <!-- Delete Hackathon Button -->
                        <form method="POST" action="manage_hackathons.php" style="display:inline-block;">
                            <input type="hidden" name="hackathon_id" value="<?php echo $hackathon['hackathon_id']; ?>">
                            <button type="submit" name="delete_hackathon" onclick="return confirm('Are you sure you want to delete this hackathon?')">Delete</button>
                        </form>
                        <!-- Update Hackathon Form -->
                        <form method="POST" action="manage_hackathons.php" style="display:inline-block;">
                            <input type="hidden" name="hackathon_id" value="<?php echo $hackathon['hackathon_id']; ?>">
                            <input type="text" name="hackathon_name" placeholder="New Name" required>
                            <textarea name="hackathon_description" placeholder="New Description" required></textarea>
                            <input type="datetime-local" name="start_date" required>
                            <input type="datetime-local" name="end_date" required>
                            <button type="submit" name="update_hackathon">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>
    </footer>
</main>

</body>
</html>
