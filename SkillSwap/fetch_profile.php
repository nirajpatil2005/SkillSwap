<?php
session_start();
include('db_connect.php');

if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

    // Query to fetch user profile information
    $profile_query = "
        SELECT u.first_name, u.last_name, ua.email, ub.bio, GROUP_CONCAT(us.skill) as skills
        FROM user_basic_info u
        JOIN user_auth ua ON u.user_id = ua.user_id
        JOIN user_skills us ON u.user_id = us.user_id
        JOIN user_basic_info ub ON u.user_id = ub.user_id
        WHERE u.user_id = '$user_id'
        GROUP BY u.user_id
    ";
    $profile_result = mysqli_query($conn, $profile_query);

    if ($profile_result && mysqli_num_rows($profile_result) > 0) {
        $profile = mysqli_fetch_assoc($profile_result);

        // Query to fetch user's previous projects
        $projects_query = "
            SELECT project_name, project_description
            FROM users_prevproj
            WHERE user_id = '$user_id'
        ";
        $projects_result = mysqli_query($conn, $projects_query);
        $projects = [];

        if ($projects_result && mysqli_num_rows($projects_result) > 0) {
            while ($project_row = mysqli_fetch_assoc($projects_result)) {
                $projects[] = [
                    'project_name' => $project_row['project_name'],
                    'project_description' => $project_row['project_description'],
                ];
            }
        }

        $profile_data = [
            'first_name' => $profile['first_name'],
            'last_name' => $profile['last_name'],
            'email' => $profile['email'],
            'bio' => $profile['bio'],
            'skills' => explode(',', $profile['skills']),
            'projects' => $projects,  // Include projects in the response
        ];
        echo json_encode($profile_data);
    } else {
        echo json_encode(['error' => 'Profile not found.']);
    }
} else {
    echo json_encode(['error' => 'User ID not provided.']);
}
?>
