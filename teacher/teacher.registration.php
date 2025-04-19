<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header("Location: index1.php");
//     exit();
// }
// Step 1: Connect to the MySQL database
$host = "localhost";
$db_user = "root";
$db_pass = "Tr0ub4dor&3l3phant";
$db_name = "user";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $faculty_id = $_POST['faculty_id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Admin sets this to phone number
    $designation = $_POST['designation'];
    $phone_number = $_POST['phone_number'];

    $sql = "INSERT INTO teacher (faculty_id, name, dob, address, pincode, state, district, email, password, designation, phone_number)
            VALUES ('$faculty_id', '$name', '$dob', '$address', '$pincode', '$state', '$district', '$email', '$password', '$designation', '$phone_number')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>Teacher registered successfully!</div>";
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .form-container {
            width: 60%;
            margin: 40px auto;
            background-color: #dcdcdc;
            padding: 20px 40px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #a02828;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #a02828;
            color: white;
            padding: 10px 15px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #722020;
        }
        .success {
            background-color: #d4edda;
            color: green;
            padding: 10px;
            border-radius: 5px;
            margin: 10px auto;
            width: 60%;
            text-align: center;
        }
        .error {
            background-color: #f8d7da;
            color: red;
            padding: 10px;
            border-radius: 5px;
            margin: 10px auto;
            width: 60%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register a New Teacher</h2>
        <form method="post" action="">
            <label for="faculty_id">Faculty ID</label>
            <input type="text" name="faculty_id" required>

            <label for="name">Name</label>
            <input type="text" name="name" required>

            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" required>

            <label for="address">Address</label>
            <input type="text" name="address" required>

            <label for="pincode">Pincode</label>
            <input type="text" name="pincode" required>

            <label for="state">State</label>
            <input type="text" name="state" required>

            <label for="district">District</label>
            <input type="text" name="district" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="designation">Designation</label>
            <input type="text" name="designation" required>

            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" required>

            <label for="password">Password (initially use phone number)</label>
            <input type="text" name="password" required>

            <button type="submit">Register Teacher</button>
        </form>
    </div>
</body>
</html>
