<?php
$servername = "localhost";
$username = "root";
$password = "MySQL@2025"; 
$dbname = "ru";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['semester_id'])) {
    $semester_id = $_GET['semester_id'];

    $sql = "SELECT message, teacher_name, timestamp FROM messages WHERE semester_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $semester_id);

    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    if (empty($messages)) {
        echo json_encode(["error" => "No messages found for the given semester ID."]);
    }
    

    echo json_encode($messages);

    $stmt->close();
}

$conn->close();
?>
