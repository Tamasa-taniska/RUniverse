<?php
$servername = "localhost";  // Change to your server (if needed)
$username = "root";         // Your MySQL username
$password = "@ashu2003";             // Your MySQL password (leave empty if using XAMPP)
$dbname = "runiverse";     // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment to verify connection
// echo "Connected successfully";
?>
