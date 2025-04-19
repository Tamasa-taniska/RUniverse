<?php
session_start();
include("db_connect.php");

// Get form input
$userType = $_POST['USER'];
$username = $_POST['username'];
$password = $_POST['password'];

// Check if it's a faculty login
if ($userType === "faculty") {
    // Query to find faculty with matching email and password
    $sql = "SELECT * FROM teacher WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // If found, redirect to profile page
    if ($result->num_rows === 1) {
        $faculty = $result->fetch_assoc();
        $_SESSION['faculty'] = $faculty;
        header("Location: tprofile.php");
        exit();
    } else {
        $_SESSION['loginMessage'] = "Invalid email or password for faculty.";
        header("Location: index1.php");
        exit();
    }
}
// else if ($userType === "admin") {
//     $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ss", $username, $password);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows === 1) {
//         $_SESSION['admin'] = true;
//         header("Location: teacher_registration.php");
//         exit();
//     } else {
//         $_SESSION['loginMessage'] = "Invalid username or password for admin.";
//         header("Location: index1.php");
//         exit();
//     }


else {
    $_SESSION['loginMessage'] = "Only 'faculty' login is implemented right now.";
    header("Location: index1.php");
    exit();
}
?>
