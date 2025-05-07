<?php
$host = "localhost";
$user = "root";
$password = "@ashu2003";
$db = "runiverse";

// Create database connection
$data = new mysqli($host, $user, $password, $db);

// Check connection
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}
?>