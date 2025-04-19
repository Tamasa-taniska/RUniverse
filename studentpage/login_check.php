<?php
session_start();
include 'dbconnect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username']; // email ID of user
    $pass = $_POST['password']; // corresponding password for user
    $role = $_POST['USER']; // User type from dropdown

    // Determine the table based on selected user type
    $valid_roles = ["students", "faculty", "admin"];
    
    if (!in_array($role, $valid_roles)) {
        $_SESSION['loginMessage'] = "Invalid user role selected.";
        header("location: login.php");
        exit();
    }
    $table= $role;
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $data->prepare($sql);
    
    // if (!$stmt) {
    //     $_SESSION['loginMessage'] = "Database error: " . $data->error;
    //     header("location: login.php");
    //     exit();
    // }

    if ($stmt === false) {
        die("SQL Error: " . $data->error); // Fix: Handle SQL errors
    }

    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verify password
    if ($row && $row["password"] === $pass) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $name;
        $_SESSION['role'] = $role; // User role

        // Redirect based on role
        if ($role == "students") {
            header("location: profile.php");
        } elseif ($role == "admin") {
            header("location: ../adminpage/adminhome.php");
        } elseif ($role == "faculty") {
            header("location: ../teacherpage/tprofile.php");
        }
        exit();
    } else {
        $_SESSION['loginMessage'] = "Invalid email or password.";
        header("location: login.php");
        exit();
    }
}
?>
