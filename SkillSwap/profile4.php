<?php
// Include database connection
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user_id from session
    $user_id = $_SESSION['user_id'];

    // Section: User Details
    if (isset($_POST['first_name'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $bio = $_POST['bio'];
        $birthdate = $_POST['birthdate'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("INSERT INTO user_basic_info (user_id, first_name, last_name, username, bio, birthdate, mobile, email)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE first_name=?, last_name=?, username=?, bio=?, birthdate=?, mobile=?, email=?");
        $stmt->bind_param("isssssssissssss", $user_id, $first_name, $last_name, $username, $bio, $birthdate, $mobile, $email,
                          $first_name, $last_name, $username, $bio, $birthdate, $mobile, $email);

        if ($stmt->execute()) {
            echo "<script>alert('User basic information saved successfully!');</script>";
        } else {
            echo "<script>alert('Error saving user basic information: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }

    // Section: More About User
    if (isset($_POST['education_level'])) {
        $education_level = $_POST['education_level'];
        $college = $_POST['college'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $experience_years = $_POST['experience_years'];

        $stmt = $conn->prepare("INSERT INTO user_education (user_id, education_level, college, city, country, experience_years)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE education_level=?, college=?, city=?, country=?, experience_years=?");
        $stmt->bind_param("issssiisssi", $user_id, $education_level, $college, $city, $country, $experience_years,
                          $education_level, $college, $city, $country, $experience_years);

        if ($stmt->execute()) {
            echo "<script>alert('User education details saved successfully!');</script>";
        } else {
            echo "<script>alert('Error saving education details: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }

    // Section: Skills
    if (isset($_POST['skills'])) {
        $skills = $_POST['skills'];  // Assuming skills are an array
        $proficiency = $_POST['proficiency'];

        // Clear previous skills
        $conn->query("DELETE FROM user_skills WHERE user_id = $user_id");

        // Insert each skill
        foreach ($skills as $skill) {
            $stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill, proficiency) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $skill, $proficiency);
            if ($stmt->execute()) {
                echo "<script>alert('Skill \'$skill\' saved successfully!');</script>";
            } else {
                echo "<script>alert('Error saving skill \'$skill\': " . $stmt->error . "');</script>";
            }
        }
        $stmt->close();
    }

 // Section: Previous Projects
if (isset($_POST['project_name'])) {
    $project_name = $_POST['project_name'];
    $project_description = $_POST['project_description'];

    $stmt = $conn->prepare("INSERT INTO users_prevproj (user_id, project_name, project_description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $project_name, $project_description);

    if ($stmt->execute()) {
        echo "<script>alert('Previous project \'$project_name\' saved successfully!');</script>";
        
        // Redirect to the sign_in.php page
        header('Location: sign_in.php');
        exit();  // Make sure to call exit after header to stop further script execution
    } else {
        echo "<script>alert('Error saving project: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}


    // Close the database connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile4.css"> <!-- Link to external CSS -->
</head>
<body>
  <video autoplay muted loop id="background-video">
    <source src="background-video.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
  </video>

  <div class="main-content">
    <header>
      <div class="logo">
        <img src="logo.png" alt="Skill Swap Logo">
      </div>   
    </header>

    <nav class="profile-nav">
        <button onclick="showSection('user-details')">User Details</button>
        <button onclick="showSection('more-about-user')">More About User</button>
        <button onclick="showSection('skills-section')">Skills</button>
        <button onclick="showSection('previous-projects')">Previous Projects</button>
    </nav>

    <section id="user-details" class="section-content">
        <h2>User Details</h2>
        <form id="user-details-form" action="profile4.php" method="POST">
            <label>First Name: <input type="text" id="first-name" name="first_name" required></label>
            <label>Last Name: <input type="text" id="last-name" name="last_name" required></label>
            <label>Username: <input type="text" id="username" name="username" required></label>
            <label>Bio: <textarea id="bio" name="bio" required></textarea></label>
            <label>Birthdate: <input type="date" id="birthdate" name="birthdate" required></label>
            <label>Mobile Number: <input type="text" id="mobile-number" name="mobile" required></label>
            <label>Email: <input type="email" id="email" name="email" required></label>
            <button type="submit" class="next-btn">Save</button>
        </form>
    </section>

    <section id="more-about-user" class="section-content" style="display: none;">
        <h2>More About User</h2>
        <form id="more-about-user-form" action="profile4.php" method="POST">
            <label>Graduate/Undergraduate:
                <select id="education-level" name="education_level">
                    <option value="undergraduate">Undergraduate</option>
                    <option value="graduate">Graduate</option>
                </select>
            </label>
            <label>College: <input type="text" id="college" name="college" required></label>
            <label>City:
                <select id="city" name="city">
                    <option value="city1">City 1</option>
                    <option value="city2">City 2</option>
                </select>
            </label>
            <label>Country:
                <select id="country" name="country">
                    <option value="country1">Country 1</option>
                    <option value="country2">Country 2</option>
                </select>
            </label>
            <label>Experience (Years): <input type="number" id="experience" name="experience_years" required></label>
            <button type="submit" class="next-btn">Save</button>
        </form>
    </section>

    <section id="skills-section" class="section-content" style="display: none;">
      <h2>Skills Section</h2>
      <form id="skills-form" action="profile4.php" method="POST">
          <label><strong>Proficiency:</strong>
            <select id="proficiency" name="proficiency">
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Professional">Professional</option>
            </select>
          </label>
          <div class="skills-container">
              <div class="skills-categories">
                  <h3>Select 1 category</h3>
                  <ul>
                      <li onclick="showSpecialties('engineering')">Engineering & Architecture</li>
                      <li onclick="showSpecialties('it-networking')">IT & Networking</li>
                      <li onclick="showSpecialties('design')">Design & Creative</li>
                  </ul>
              </div>
              <div class="skills-specialties">
                  <h3>Select 1 to 3 specialties</h3>
                  <div id="engineering-specialties" class="specialty-list" style="display: none;">
                      <label><input type="checkbox" name="skills[]" value="building-landscape">Building & Landscape</label>
                      <label><input type="checkbox" name="skills[]" value="civil-engineering">Civil Engineering</label>
                      <label><input type="checkbox" name="skills[]" value="mechanical-engineering">Mechanical Engineering</label>
                  </div>
                  <div id="it-networking-specialties" class="specialty-list" style="display: none;">
                      <label><input type="checkbox" name="skills[]" value="networking">Networking</label>
                      <label><input type="checkbox" name="skills[]" value="cloud-computing">Cloud Computing</label>
                  </div>
                  <div id="design-specialties" class="specialty-list" style="display: none;">
                      <label><input type="checkbox" name="skills[]" value="graphic-design">Graphic Design</label>
                      <label><input type="checkbox" name="skills[]" value="ux-ui">UX/UI</label>
                  </div>
              </div>
          </div>
          <button type="submit" class="next-btn">Save</button>
      </form>
    </section>

    <section id="previous-projects" class="section-content" style="display: none;">
        <h2>Previous Projects</h2>
        <form id="previous-projects-form" action="profile4.php" method="POST">
            <label>Project Name: <input type="text" id="project-name" name="project_name" required></label>
            <label>Project Description: <textarea id="project-description" name="project_description" required></textarea></label>
            <button type="submit" class="next-btn">Submit</button>
        </form>
    </section>
  </div>

  <script>
      function showSection(sectionId) {
          // Hide all sections
          const sections = document.querySelectorAll('.section-content');
          sections.forEach(section => {
              section.style.display = 'none';
          });
          // Show the selected section
          document.getElementById(sectionId).style.display = 'block';
      }

      function showSpecialties(category) {
          const specialtyLists = document.querySelectorAll('.specialty-list');
          specialtyLists.forEach(list => {
              list.style.display = 'none'; // Hide all specialties
          });
          document.getElementById(category + '-specialties').style.display = 'block'; // Show selected specialties
      }
  </script>
</body>
</html>
