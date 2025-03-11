<?php
session_start();
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $name = $_POST['username']; //email id
    $pass = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $data->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verify password
    if ($row && $row["password"] === $pass) 
    { 
        $_SESSION['username'] = $name; //email of user
        $_SESSION['role'] = $row["role"];

        if ($row["role"] == "Student") {
            header("location: profile.php");
        } elseif ($row["role"] == "Admin") {
            header("location: adminhome.html");
        }
        exit();
    } else {
        $_SESSION['loginMessage'] = "Username or password do not match";
        header("location: login.php");
        exit();
    }
}
?>
