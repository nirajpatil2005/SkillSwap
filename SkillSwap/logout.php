<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to open.html after logout
header("Location: open.php");
exit();
?>
