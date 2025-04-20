<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve input values from the POST request
    $semester_id = $_POST['semester_id'];
    $message = $_POST['message'];
    $teacher_name = $_POST['teacher_name'];

    // Validate that all fields are filled
    if (empty($semester_id) || empty($message) || empty($teacher_name)) {
        die("All fields are required!");
    }

    // Prepare SQL query to insert data into the messages table
    $sql = "INSERT INTO messages (semester_id, message, teacher_name) VALUES (?, ?, ?)";
    $stmt = $data->prepare($sql);
    $stmt->bind_param("iss", $semester_id, $message, $teacher_name);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo "Message uploaded successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}
?>