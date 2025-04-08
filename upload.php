<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "Tr0ub4dor&3l3phant"; // Replace with your MySQL password
$dbname = "school";

// Establish connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted via POST
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
    $stmt = $conn->prepare($sql);
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

// Close the database connection
$conn->close();
?>
