<?php
$servername = "localhost";
$username = "root";
$password = "MySQL@2025";
$database = "ru";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional but recommended
$conn->set_charset("utf8mb4");
?>
