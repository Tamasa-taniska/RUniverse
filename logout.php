<?php
session_start();
session_destroy(); // Destroys all session data
header("Location: index1.php"); // Redirects to login page
exit();
?>
