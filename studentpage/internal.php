<?php
// Start session and check if student is logged in
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "MySQL@2025", "marksheet_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student details
$studentId = $_SESSION['student_id'];
$sql = "SELECT name, marksheet_path FROM students WHERE id = $studentId";
$result = $conn->query($sql);
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Scorecard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e6f7ff;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1, p {
            text-align: center;
            color: #333333;
        }

        .button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            border: none;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .logout {
            margin-top: 15px;
            display: block;
            text-align: center;
            color: #ff0000;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $student['name']; ?></h1>
        <?php if ($student['marksheet_path']): ?>
            <p>Your marksheet is ready to download:</p>
            <a href="<?php echo $student['marksheet_path']; ?>" class="button" download>Download Marksheet</a>
        <?php else: ?>
            <p>No marksheet available at the moment.</p>
        <?php endif; ?>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>
