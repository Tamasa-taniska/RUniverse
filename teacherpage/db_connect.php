<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Default XAMPP MySQL username
$password = "Tr0ub4dor&3l3phant"; // Default XAMPP MySQL password (empty)
$dbname = "user"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
