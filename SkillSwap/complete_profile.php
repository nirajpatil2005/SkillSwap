<?php
// Include the database connection
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM user_auth WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile</title>
</head>
<body>
    <h2>Complete Your Profile</h2>
    <form action="save_profile.php" method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        <br>
        <!-- Add more fields as necessary -->
        <button type="submit">Save Profile</button>
    </form>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>



  <link rel="stylesheet" href="profile.css"> <!-- Link to your CSS file -->
</head>
<body>
  <!-- Header Section -->
  <header class="header">
    <img src="logo2.png" alt="Logo" class="logo">
          <a href="home.php">
              <button class="Profile">Home</button>
          </a>
  </header>

  <!-- Navigation for different sections -->
  <nav class="nav">
    <button class="tab-button" onclick="toggleSection('details-section')">User Details</button>
    <button class="tab-button" onclick="toggleSection('more-about-section')">More About User</button>
    <button class="tab-button" onclick="toggleSection('skills-section')">Skills</button>
    <button class="tab-button" onclick="toggleSection('projects-section')">Previous Projects</button>
  </nav>

  <!-- Main Profile Sections -->

  <!-- User Details Section (Visible by default) -->
<!-- User Details Section -->
<div id="details-section" class="section">
  <h2>Personal Details</h2>
  
  <!-- View-only mode -->
  <div id="view-details">
    <p>First Name: <span id="view-first-name">John</span></p>
    <p>Last Name: <span id="view-last-name">Doe</span></p>
    <p>Username: <span id="view-username">johndoe</span></p>
    <p>Bio: <span id="view-bio">Software Engineer</span></p>
    <p>Birthdate: <span id="view-birthdate">1990-01-01</span></p>
    <p>Mobile Number: <span id="view-mobile">1234567890</span></p>
    <p>Email ID: <span id="view-email">john@example.com</span></p>
  </div>

  <!-- Editable form -->
  <form id="edit-details" class="hidden">
    <label>First Name: <input type="text" id="first-name" value="John"></label>
    <label>Last Name: <input type="text" id="last-name" value="Doe"></label>
    <label>Username: <input type="text" id="username" value="johndoe"></label>
    <label>Bio: <textarea id="bio">Software Engineer</textarea></label>
    <label>Birthdate: <input type="date" id="birthdate" value="1990-01-01"></label>
    <label>Mobile Number: <input type="text" id="mobile" value="1234567890"></label>
    <label>Email ID: <input type="email" id="email" value="john@example.com"></label>
  </form>

  <button type="button" id="edit-details-btn" class="edit-btn" onclick="enableEdit('details')">Edit Details</button>
  <button type="button" id="save-details-btn" class="save-btn hidden" onclick="saveDetails('details')">Save Changes</button>
</div>

<div id="more-about-section" class="section hidden">
  <h2>Educational Information</h2>
  
  <!-- View-only mode -->
  <div id="view-more-about">
    <p>Education Level: <span id="view-education-level">Graduate</span></p>
    <p>College: <span id="view-college">ABC University</span></p>
    <p>City: <span id="view-city">New York</span></p>
    <p>Country: <span id="view-country">USA</span></p>
    <p>Known Languages: <span id="view-languages">English, Spanish</span></p>
    <p>Experience: <span id="view-experience">5 years</span></p>
  </div>

  <!-- Editable form -->
  <form id="edit-more-about" class="hidden">
    <label>Education Level: 
      <select id="education-level">
        <option>Graduate</option>
        <option>Undergraduate</option>
      </select>
    </label>
    <label>College: <input type="text" id="college" value="ABC University"></label>
    <label>City: <select id="city">
      <option>New York</option>
      <option>Los Angeles</option>
    </select></label>
    <label>Country: <select id="country">
      <option>USA</option>
      <option>Canada</option>
    </select></label>
    <label>Known Languages (hold ctrl to select multiple): 
      <select multiple id="languages">
        <option>English</option>
        <option>Spanish</option>
        <option>French</option>
      </select>
    </label>
    <label>Experience: <input type="number" id="experience" value="5"></label>
  </form>

  <button type="button" id="edit-more-about-btn" class="edit-btn" onclick="enableEdit('more-about')">Edit Details</button>
  <button type="button" id="save-more-about-btn" class="save-btn hidden" onclick="saveDetails('more-about')">Save Changes</button>
</div>

<!-- Skills Section (Hidden by default) -->
<div id="skills-section" class="section hidden">
  <h2>Skills</h2>
  
  <!-- This is the non-editable view where the selected skills are displayed -->
  <div id="view-skills">
    <h3>Selected Skills:</h3>
    <ul id="view-selected-skills-list">
      <!-- Skills will be listed here when not editing -->
    </ul>
  </div>
  
  <!-- This is the editable form that appears when the user clicks 'Edit Details' -->
  <div id="edit-skills" class="hidden">
    <div class="skills-container">
      <div class="skill-categories">
        <ul>
          <li class="category-item" onclick="showSkills('engineering')">Engineering & Architecture</li>
          <li class="category-item" onclick="showSkills('design')">Design & Creative</li>
          <li class="category-item" onclick="showSkills('data-science')">Data Science & Analytics</li>
          <li class="category-item" onclick="showSkills('it')">IT & Networking</li>
          <!-- Add more categories as needed -->
        </ul>
      </div>
      <div class="skills-list">
        <!-- Engineering & Architecture Skills -->
        <div id="engineering" class="skill-group hidden">
          <label><input type="checkbox" class="skill-checkbox" value="Civil Engineering"> Civil Engineering</label>
          <label><input type="checkbox" class="skill-checkbox" value="Mechanical Engineering"> Mechanical Engineering</label>
          <label><input type="checkbox" class="skill-checkbox" value="Electrical Engineering"> Electrical Engineering</label>
        </div>

        <!-- Design & Creative Skills -->
        <div id="design" class="skill-group hidden">
          <label><input type="checkbox" class="skill-checkbox" value="Graphic Design"> Graphic Design</label>
          <label><input type="checkbox" class="skill-checkbox" value="UI/UX Design"> UI/UX Design</label>
          <label><input type="checkbox" class="skill-checkbox" value="3D Modeling"> 3D Modeling</label>
        </div>

        <!-- Data Science & Analytics Skills -->
        <div id="data-science" class="skill-group hidden">
          <label><input type="checkbox" class="skill-checkbox" value="Data Analysis"> Data Analysis</label>
          <label><input type="checkbox" class="skill-checkbox" value="Machine Learning"> Machine Learning</label>
          <label><input type="checkbox" class="skill-checkbox" value="Data Visualization"> Data Visualization</label>
        </div>

        <!-- IT & Networking Skills -->
        <div id="it" class="skill-group hidden">
          <label><input type="checkbox" class="skill-checkbox" value="Network Security"> Network Security</label>
          <label><input type="checkbox" class="skill-checkbox" value="Cloud Computing"> Cloud Computing</label>
          <label><input type="checkbox" class="skill-checkbox" value="System Administration"> System Administration</label>
        </div>
      </div>
    </div>
   <!-- Selected Skills Area in Edit Mode -->
   <div id="selected-skills">
      <h3>Selected Skills:</h3>
      <ul id="selected-skills-list">
        <!-- Selected skills will appear here -->
      </ul>
    </div>
  </div>

  <button type="button" id="edit-skills-btn" class="edit-btn" onclick="enableEdit('skills')">Edit Details</button>
  <button type="button" id="save-skills-btn" class="save-btn hidden" onclick="saveDetails('skills')">Save Changes</button>
</div>

  <!-- Previous Projects Section (Hidden by default) -->
 <!-- Projects Section -->
<div id="projects-section" class="section hidden">
  <h2>Previous Projects</h2>
  
  <!-- View-only mode -->
  <div id="view-projects">
    <p>Project Description: <span id="view-project-description">Sample Project</span></p>
    <p>CV Uploaded: <span id="view-cv">No</span></p>
  </div>

  <!-- Editable form -->
  <form id="edit-projects" class="hidden">
    <label>Upload Projects: <input type="file" id="projects" multiple></label>
    <label>Project Description: <textarea id="project-description">Sample Project</textarea></label>
    <label>Upload CV: <input type="file" id="cv"></label>
  </form>

  <button type="button" id="edit-projects-btn" class="edit-btn" onclick="enableEdit('projects')">Edit Details</button>
  <button type="button" id="save-projects-btn" class="save-btn hidden" onclick="saveDetails('projects')">Save Changes</button>
</div>

  <!-- Footer -->
<footer>
    <p>Contact us at: <a href="mailto:team.skillswap@gmail.com">team.skillswap@gmail.com</a></p>
    <p>Created by: Atharva Jain | Niraj Patil</p>
</footer>

  <script src="profile.js"></script> <!-- Link to your JS file -->
</body>
</html>
