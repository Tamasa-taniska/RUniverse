
<?php
$host = "localhost";
$user = "root";
$password = "MySQL@2025";
$db = "ru";

// Create database connection
$data = new mysqli($host, $user, $password, $db);

// Check connection
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}
?>
