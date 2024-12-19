<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = 'Niraj@$123';
$database = 'skills_swap';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch hackathons
function fetchHackathons($conn) {
    $sql = "SELECT * FROM hackathons"; // Adjust as necessary
    $result = $conn->query($sql);
    
    $hackathons = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hackathons[] = $row; // Collect each hackathon into an array
        }
    }
    return $hackathons;
}

// Fetch hackathons
$hackathons = fetchHackathons($conn);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hackathon Updates | SkillSwap</title>
    <link rel="stylesheet" href="hackathon-updates.css">
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
                <li><a href="home.php"><button class="Profile">Home</button></a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Section: Hackathon Updates -->
    <section class="updates-section">
        <div class="updates-container">
            <div class="updates-header">
                <h2>Latest Hackathon Updates</h2>
                <p>Stay updated with the latest hackathons, competitions, and coding challenges happening around the world!</p>
            </div>
            
            <div class="updates-list">
                <?php if (!empty($hackathons)): ?>
                    <?php foreach ($hackathons as $hackathon): ?>
                        <div class="update-card">
                            <h3><?php echo htmlspecialchars($hackathon["hackathon_name"]); ?></h3>
                            <p><?php echo htmlspecialchars($hackathon["description"] ?? 'No description available.'); ?></p>
                            <p class="date">Date: <?php echo htmlspecialchars($hackathon["start_date"] . ' - ' . $hackathon["end_date"]); ?></p>
                            <button class="view-details-btn" data-modal="modal<?php echo $hackathon["id"]; ?>">View Details</button>
                        </div>

                        <!-- Modal for each hackathon -->
                        <div id="modal<?php echo $hackathon["id"]; ?>" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <h2><?php echo htmlspecialchars($hackathon["hackathon_name"]); ?></h2>
                                <p><?php echo htmlspecialchars($hackathon["description"] ?? 'No description available.'); ?></p>
                                <p class="date">Date: <?php echo htmlspecialchars($hackathon["start_date"] . ' - ' . $hackathon["end_date"]); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($hackathon["location"] ?? 'N/A'); ?></p>
                                <p><strong>Organizers:</strong> <?php echo htmlspecialchars($hackathon["organizers"] ?? 'N/A'); ?></p>
                                <p><strong>Prizes:</strong> <?php echo htmlspecialchars($hackathon["prizes"] ?? 'N/A'); ?></p>
                                <p><strong>Additional Info:</strong> <?php echo htmlspecialchars($hackathon["additional_info"] ?? 'N/A'); ?></p>
                                <a href="<?php echo htmlspecialchars($hackathon["registration_link"] ?? '#'); ?>" target="_blank" class="view-details-btn">Register Now</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hackathon updates available at this time.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
        <p>Created by: Atharva Jain | Niraj Patil | Viraj Jadhav</p>
    </footer>

    <script src="hackathon-updates.js"></script>
</body>
</html>
