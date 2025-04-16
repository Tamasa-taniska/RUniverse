<?php
$host = "localhost";
$user = "root";
$pass = "@ashu2003";
$db = "runiverse";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
