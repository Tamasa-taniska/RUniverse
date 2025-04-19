<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "MySQL@2025";
$dbname = "ru";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $dateOfBirth = $conn->real_escape_string($_POST['dateOfBirth']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $course = $conn->real_escape_string($_POST['course']);
    $branch = $conn->real_escape_string($_POST['branch']);
    $bloodGroup = $conn->real_escape_string($_POST['bloodGroup']);
    $rollNo = $conn->real_escape_string($_POST['rollNo']);
    $motherName = $conn->real_escape_string($_POST['motherName']);
    $district = $conn->real_escape_string($_POST['district']);
    $pinCode = $conn->real_escape_string($_POST['pinCode']);
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "UPDATE students SET 
            studentName = '$studentName',
            dateOfBirth = '$dateOfBirth',
            address = '$address',
            city = '$city',
            state = '$state',
            mobile = '$mobile',
            course = '$course',
            branch = '$branch',
            bloodGroup = '$bloodGroup',
            motherName = '$motherName',
            district = '$district',
            pinCode = '$pinCode',
            email = '$email'
            WHERE rollNo = '$rollNo'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>
