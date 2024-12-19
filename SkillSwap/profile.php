<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$first_name = $last_name = $username = $bio = $birthdate = $mobile = $email = '';
$education_level = $college = $city = $country = $experience_years = '';
$skills = ''; 
$projects = [];
$message = '';

// Handle updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_skills'])) {
        // Skill update logic
        $new_skills = array_filter(array_map('trim', explode(',', $_POST['skills'])));
        
        // Use a transaction to ensure atomicity
        $conn->begin_transaction();
        try {
            $delete_sql = "DELETE FROM user_skills WHERE user_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            foreach ($new_skills as $skill) {
                if (!empty($skill)) {
                    $insert_sql = "INSERT INTO user_skills (user_id, skill) VALUES (?, ?)";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("is", $user_id, $skill);
                    $stmt->execute();
                }
            }
            $stmt->close();
            $conn->commit();
            $message = "Skills updated successfully.";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error updating skills: " . $e->getMessage();
        }
    } elseif (isset($_POST['update_details'])) {
        // User detail update logic
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $bio = $_POST['bio'];
        $birthdate = $_POST['birthdate'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        
        $update_sql = "UPDATE user_basic_info SET first_name = ?, last_name = ?, bio = ?, birthdate = ?, mobile = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $bio, $birthdate, $mobile, $email, $user_id);
        $stmt->execute();
        $stmt->close();
        $message = "User details updated successfully.";
    } elseif (isset($_POST['update_education'])) {
        // Education update logic
        $education_level = $_POST['education_level'];
        $college = $_POST['college'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $experience_years = $_POST['experience_years'];

        $update_sql = "UPDATE user_education SET education_level = ?, college = ?, city = ?, country = ?, experience_years = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssssi", $education_level, $college, $city, $country, $experience_years, $user_id);
        $stmt->execute();
        $stmt->close();
        $message = "Education updated successfully.";
    } elseif (isset($_POST['update_projects'])) {
        // Project update logic
        $project_name = $_POST['project_name'];
        $project_description = $_POST['project_description'];

        // Update previous projects in the database
        $update_sql = "UPDATE users_prevproj SET project_name = ?, project_description = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $project_name, $project_description, $user_id);
        $stmt->execute();
        $stmt->close();
        $message = "Projects updated successfully.";
    }
}

// Retrieve user data from different tables
$sql = "SELECT ub.first_name, ub.last_name, ub.username, ub.bio, ub.birthdate, ub.mobile, ub.email, 
        ue.education_level, ue.college, ue.city, ue.country, ue.experience_years, 
        up.project_name, up.project_description
        FROM user_basic_info ub 
        LEFT JOIN user_education ue ON ub.user_id = ue.user_id
        LEFT JOIN users_prevproj up ON ub.user_id = up.user_id
        WHERE ub.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = null;

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

// Assign user data to variables
if ($user) {
    $first_name = htmlspecialchars($user['first_name']);
    $last_name = htmlspecialchars($user['last_name']);
    $username = htmlspecialchars($user['username']);
    $bio = htmlspecialchars($user['bio']);
    $birthdate = htmlspecialchars($user['birthdate']);
    $mobile = htmlspecialchars($user['mobile']);
    $email = htmlspecialchars($user['email']);
    $education_level = htmlspecialchars($user['education_level']);
    $college = htmlspecialchars($user['college']);
    $city = htmlspecialchars($user['city']);
    $country = htmlspecialchars($user['country']);
    $experience_years = htmlspecialchars($user['experience_years']);
    $projects[] = [
        'name' => htmlspecialchars($user['project_name']),
        'description' => htmlspecialchars($user['project_description'])
    ];
}

// Fetch skills separately
$sql = "SELECT skill FROM user_skills WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$userSkills = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['skill'])) {
        $userSkills[] = htmlspecialchars($row['skill']);
    }
}

// Join skills into a single string for display
$skills = implode(', ', $userSkills);
if (empty($skills)) {
    $skills = "No skills available.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="profile3.css">
</head>
<body>
  <header class="header">
    <img src="logo2.png" alt="Logo" class="logo">
    <a href="home.php">
      <button class="Profile">Home</button>
    </a>
  </header>

  <nav class="nav">
    <button class="tab-button" onclick="toggleSection('details-section')">User Details</button>
    <button class="tab-button" onclick="toggleSection('more-about-section')">More About User</button>
    <button class="tab-button" onclick="toggleSection('skills-section')">Skills</button>
    <button class="tab-button" onclick="toggleSection('projects-section')">Previous Projects</button>
  </nav>

  <?php if ($message): ?>
    <div class="alert"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

 <!-- User Details -->
<div id="details-section" class="section">
  <h2>User Details</h2>
  <div id="view-details">
    <p>First Name: <span id="view-first-name"><?php echo $first_name; ?></span></p>
    <p>Last Name: <span id="view-last-name"><?php echo $last_name; ?></span></p>
    <p>Username: <span id="view-username"><?php echo $username; ?></span></p>
    <p>Bio: <span id="view-bio"><?php echo $bio; ?></span></p>
    <p>Birthdate: <span id="view-birthdate"><?php echo $birthdate; ?></span></p>
    <p>Mobile Number: <span id="view-mobile"><?php echo $mobile; ?></span></p>
    <p>Email ID: <span id="view-email"><?php echo $email; ?></span></p>
  </div>
  <button onclick="document.getElementById('edit-details-modal').style.display='block'">Edit User Details</button>
</div>

<!-- Edit User Details Modal -->
<div id="edit-details-modal" class="modal" style="display:none;">
  <div class="modal-content">
    <span onclick="document.getElementById('edit-details-modal').style.display='none'" class="close">&times;</span>
    <h2>Edit User Details</h2>
    <form method="POST" action="">
      <label for="first_name">First Name:</label>
      <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
      
      <label for="last_name">Last Name:</label>
      <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
      
      <label for="bio">Bio:</label>
      <textarea id="bio" name="bio" required><?php echo htmlspecialchars($bio); ?></textarea>
      
      <label for="birthdate">Birthdate:</label>
      <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" required>
      
      <label for="mobile">Mobile Number:</label>
      <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" required>
      
      <label for="email">Email ID:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
      
      <button type="submit" name="update_details">Update Details</button>
    </form>
  </div>
</div>

<!-- More About User Section -->
<div id="more-about-section" class="section hidden">
  <h2>More About User</h2>
  <div id="view-education">
    <p>Education Level: <span id="view-education-level"><?php echo $education_level; ?></span></p>
    <p>College: <span id="view-college"><?php echo $college; ?></span></p>
    <p>City: <span id="view-city"><?php echo $city; ?></span></p>
    <p>Country: <span id="view-country"><?php echo $country; ?></span></p>
    <p>Experience Years: <span id="view-experience-years"><?php echo $experience_years; ?></span></p>
  </div>
  <button onclick="document.getElementById('edit-education-modal').style.display='block'">Edit Education</button>
</div>

<!-- Edit Education Modal -->
<div id="edit-education-modal" class="modal" style="display:none;">
  <div class="modal-content">
    <span onclick="document.getElementById('edit-education-modal').style.display='none'" class="close">&times;</span>
    <h2>Edit Education</h2>
    <form method="POST" action="">
      <label for="education_level">Education Level:</label>
      <input type="text" id="education_level" name="education_level" value="<?php echo htmlspecialchars($education_level); ?>" required>
      
      <label for="college">College:</label>
      <input type="text" id="college" name="college" value="<?php echo htmlspecialchars($college); ?>" required>
      
      <label for="city">City:</label>
      <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
      
      <label for="country">Country:</label>
      <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
      
      <label for="experience_years">Experience Years:</label>
      <input type="number" id="experience_years" name="experience_years" value="<?php echo htmlspecialchars($experience_years); ?>" required>
      
      <button type="submit" name="update_education">Update Education</button>
    </form>
  </div>
</div>

<!-- Skills Section -->
<div id="skills-section" class="section hidden">
  <h2>Skills</h2>
  <p id="view-skills"><?php echo $skills; ?></p>
  <button onclick="document.getElementById('edit-skills-modal').style.display='block'">Edit Skills</button>
</div>

<!-- Edit Skills Modal -->
<div id="edit-skills-modal" class="modal" style="display:none;">
  <div class="modal-content">
    <span onclick="document.getElementById('edit-skills-modal').style.display='none'" class="close">&times;</span>
    <h2>Edit Skills</h2>
    <form method="POST" action="">
      <label for="skills">Skills (comma separated):</label>
      <input type="text" id="skills" name="skills" value="<?php echo htmlspecialchars($skills); ?>">
      
      <button type="submit" name="update_skills">Update Skills</button>
    </form>
  </div>
</div>

<!-- Projects Section -->
<div id="projects-section" class="section hidden">
  <h2>Previous Projects</h2>
  <div id="view-projects">
    <?php foreach ($projects as $project): ?>
      <p>Project Name: <span id="view-project-name"><?php echo htmlspecialchars($project['name']); ?></span></p>
      <p>Project Description: <span id="view-project-description"><?php echo htmlspecialchars($project['description']); ?></span></p>
    <?php endforeach; ?>
  </div>
  <button onclick="document.getElementById('edit-project-modal').style.display='block'">Edit Projects</button>
</div>

<!-- Edit Projects Modal -->
<div id="edit-project-modal" class="modal" style="display:none;">
  <div class="modal-content">
    <span onclick="document.getElementById('edit-project-modal').style.display='none'" class="close">&times;</span>
    <h2>Edit Projects</h2>
    <form method="POST" action="">
      <label for="project_name">Project Name:</label>
      <input type="text" id="project_name" name="project_name" value="<?php echo htmlspecialchars($projects[0]['name'] ?? ''); ?>" required>
      
      <label for="project_description">Project Description:</label>
      <textarea id="project_description" name="project_description" required><?php echo htmlspecialchars($projects[0]['description'] ?? ''); ?></textarea>
      
      <button type="submit" name="update_projects">Update Projects</button>
    </form>
  </div>
</div>
<footer>
  <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
  <p>&copy; 2024 SkillSwap. All rights reserved.</p>
</footer>

<script>
function toggleSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        if (section.id === sectionId) {
            section.classList.toggle('hidden');
        } else {
            section.classList.add('hidden');
        }
    });
}
</script>
</body>
</html>
