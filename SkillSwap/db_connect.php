<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "Niraj@$123";
$db_name = "skills_swap";
$conn = "";

try {
    // Create the connection
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    // Check connection
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    // Optional: Set UTF-8 encoding
    mysqli_set_charset($conn, "utf8mb4");

    // Example query to test the connection
    $sql = "SELECT 'Connected successfully' AS message";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // echo "Message from database: " . $row['message'];
    
    // Example PDO-style query (not recommended if using mysqli)
    // $pdo = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_pass);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $stmt = $pdo->prepare("SELECT 'Connected successfully' AS message");
    // $stmt->execute();
    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo "Message from database: " . $row['message'];
    
} catch (Exception $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}
?>
