<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    echo "Redirecting to login page...<br>";
    session_destroy();
    header("location: ../studentpage/login.php");
    exit();
}

include("../studentpage/dbconnect.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? NULL;
    $last_name = $_POST['last_name'];
    $pincode = $_POST['pincode'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $house_number = $_POST['house_no'];
    $road_name = $_POST['road_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $photo = 0;

    // Step 1: Insert into users table
    $sql_users = "INSERT INTO users (
        first_name, middle_name, last_name, role,
        pincode, State, City, House_No_Building_Name, Road_Name_Area_Colony
    ) VALUES (?, ?, ?, 'Admin', ?, ?, ?, ?, ?)";

    $stmt_users = $data->prepare($sql_users);
    if (!$stmt_users) {
        die("Prepare failed for users: " . $data->error);
    }

    $stmt_users->bind_param(
        "ssssssss",
        $first_name, $middle_name, $last_name,
        $pincode, $state, $city, $house_number, $road_name
    );

    if ($stmt_users->execute()) {
        // Get the new user_id inserted by trigger
        $new_admin_id = $stmt_users->insert_id;

        // Step 2: Update email and password in admin table
        $sql_admin = "UPDATE admin SET email = ?, password = ?, photo = ? WHERE admin_id = ?";
        $stmt_admin = $data->prepare($sql_admin);
        if (!$stmt_admin) {
            die("Prepare failed for admin: " . $data->error);
        }

        $stmt_admin->bind_param("ssii", $email, $password, $photo, $new_admin_id);

        if ($stmt_admin->execute()) {
            echo "<script>
                alert('Admin registered successfully!');
                window.location.href = '../adminpage/adminhome.php';
            </script>";
        } else {
            echo "Admin update failed: " . $stmt_admin->error;
        }

        $stmt_admin->close();
    } else {
        echo "Users insert failed: " . $stmt_users->error;
    }

    $stmt_users->close();
    $data->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"], button {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('a_header.php')
        .then(res => res.text())
        .then(data => document.getElementById('header-placeholder').innerHTML = data);
</script>

<div class="container">
    <h1>Register New Admin</h1>
    <form method="POST">
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="form-group">
            <label>Middle Name:</label>
            <input type="text" name="middle_name">
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="form-group">
            <label>Pincode:</label>
            <input type="text" name="pincode" required>
        </div>
        <div class="form-group">
            <label>State:</label>
            <input type="text" name="state" required>
        </div>
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="city" required>
        </div>
        <div class="form-group">
            <label>House No/Building Name:</label>
            <input type="text" name="house_no" required>
        </div>
        <div class="form-group">
            <label>Road Name/Area/Colony:</label>
            <input type="text" name="road_name" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Register Admin</button>
    </form>
</div>
</body>
</html>