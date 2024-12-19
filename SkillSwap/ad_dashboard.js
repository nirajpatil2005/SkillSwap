// admin_dashboard.js

document.addEventListener("DOMContentLoaded", function() {
    // Event listener for user card click
    document.getElementById("userCard").addEventListener("click", function() {
        window.location.href = "manage_users.php"; // Redirect to manage users page
    });

    // Event listener for project card click
    document.getElementById("projectCard").addEventListener("click", function() {
        window.location.href = "manage_projects.php"; // Redirect to manage projects page
    });

    // Event listener for hackathon card click
    document.getElementById("hackathonCard").addEventListener("click", function() {
        window.location.href = "manage_hackathons.php"; // Redirect to manage hackathons page
    });
});
