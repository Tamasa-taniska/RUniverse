<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    echo "Redirecting to login page...<br>";
    session_destroy();
    header("location: ../studentpage/login.php");
    exit();
}

$email = $_SESSION['email']; // Admin's email

include("../studentpage/dbconnect.php");

// Error and success messages
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? NULL; // Optional middle name
    $last_name = $_POST['last_name'];
    $pincode = $_POST['pincode'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $house_number = $_POST['house_number'];
    $road_name = $_POST['road_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash password
    $photo = 0; // Default photo placeholder

    $admin_id = $_POST['admin_id']; // Admin ID to update

    // Step 1: Update users table
    $sql_users_update = "UPDATE users 
                         SET first_name = ?, middle_name = ?, last_name = ?, 
                             pincode = ?, State = ?, City = ?, 
                             House_No_Building_Name = ?, Road_Name_Area_Colony = ? 
                         WHERE user_id = ?";
    $stmt_users = $data->prepare($sql_users_update);
    if (!$stmt_users) {
        die("Prepare failed for users table: " . $data->error);
    }

    $stmt_users->bind_param(
        "ssssssssi",
        $first_name,
        $middle_name,
        $last_name,
        $pincode,
        $state,
        $city,
        $house_number,
        $road_name,
        $admin_id // user_id matches admin_id as per the foreign key constraint
    );

    if ($stmt_users->execute()) {
        // Step 2: Update admin table
        $sql_admin_update = "UPDATE admin 
                             SET email = ?, password = ?, photo = ? 
                             WHERE admin_id = ?";
        $stmt_admin = $data->prepare($sql_admin_update);
        if (!$stmt_admin) {
            die("Prepare failed for admin table: " . $data->error);
        }

        $stmt_admin->bind_param("sssi", $email, $password, $photo, $admin_id);

        if ($stmt_admin->execute()) {
            // Success message
            $message = "Admin updated successfully for user ID: $admin_id";
            echo "<script>alert(" . json_encode($message) . ");
            window.location.href = '../adminhome.php';
            </script>";
        } else {
            echo "Error updating admin table: " . $stmt_admin->error;
        }

        $stmt_admin->close();
    } else {
        echo "Error updating users table: " . $stmt_users->error;
    }

    $stmt_users->close();
    $data->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="email"], input[type="password"], button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #333;
            color: rgb(164, 21, 21);
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #555;
        }

        .message {
            text-align: center;
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>
    <script>
        // Load the header content from header.php
        fetch('a_header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>
<div class="container">
    <h1>Admin Registration</h1>
    <?php if ($error) echo "<p class='message'>$error</p>"; ?>
    <?php if ($success) echo "<p class='message success'>$success</p>"; ?>
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
