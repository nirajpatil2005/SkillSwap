<?php
session_start();
include('db_connect.php');

if (isset($_GET['skill'])) {
    $skill = mysqli_real_escape_string($conn, $_GET['skill']);

    // Query to fetch users with the selected skill
    $teammate_query = "
        SELECT u.user_id, u.first_name, u.last_name, ua.email, GROUP_CONCAT(us.skill) as skills
        FROM user_basic_info u
        JOIN user_auth ua ON u.user_id = ua.user_id
        JOIN user_skills us ON u.user_id = us.user_id
        WHERE us.skill = '$skill'
        GROUP BY u.user_id
    ";
    $teammate_result = mysqli_query($conn, $teammate_query);

    $teammates = [];
    if ($teammate_result && mysqli_num_rows($teammate_result) > 0) {
        while ($row = mysqli_fetch_assoc($teammate_result)) {
            $teammates[] = [
                'user_id' => $row['user_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'skills' => explode(',', $row['skills']),
            ];
        }
    }

    // Return the data as JSON
    echo json_encode($teammates);
} else {
    // Handle the case where skill is not set
    echo json_encode(['error' => 'Skill not specified.']);
}
?>
