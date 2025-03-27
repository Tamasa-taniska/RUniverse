<?php
session_start();
$host = "localhost";
$user = "root";
$password = "@ashu2003";
$db = "runiverse";

// Create database connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

if (!isset($_SESSION['email']) || $_SESSION['role'] !== "Student") {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

$email = $_SESSION['email'];

// Fetch user details from `users` table
$sql = "SELECT u.first_name, u.last_name, u.email, u.role, u.State, u.City, u.picode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               s.roll_number, s.dob, s.department, s.status, s.phone_number 
        FROM users u 
        LEFT JOIN students s ON u.user_id = s.student_id
        WHERE u.email = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// end
if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Student data not found"]);
}
?>
