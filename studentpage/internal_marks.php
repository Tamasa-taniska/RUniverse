<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "students") {
    session_destroy();
    header("location: login.php");
    exit();
}

include 'dbconnect.php';
$email = $_SESSION['email'];

// Fetch the student's roll number and ID using their email
$query_student = "SELECT roll_number, student_id FROM students WHERE email = ?";
$stmt_student = $data->prepare($query_student);
$stmt_student->bind_param("s", $email);
$stmt_student->execute();
$result_student = $stmt_student->get_result();
if ($result_student->num_rows > 0) {
    $student_data = $result_student->fetch_assoc();
    $roll_number = $student_data['roll_number'];
    $student_id = $student_data['student_id'];
} else {
    echo "No student found for the provided email.";
    exit();
}

// Fetch student marks along with subjects
$query_marks = "
    SELECT 
        s.subject_name, 
        s.semester, 
        s.max_marks, 
        im.marks, 
        im.entry_date
    FROM 
        internal_marks im
    JOIN 
        subjects s ON im.subject_id = s.subject_id
    WHERE 
        im.student_id = ?
    ORDER BY 
        s.semester, s.subject_name
";

$stmt_marks = $data->prepare($query_marks);
if (!$stmt_marks) {
    die("SQL prepare failed: " . $data->error);
}
$stmt_marks->bind_param("i", $student_id); // Use student_id for filtering
$stmt_marks->execute();
$result_marks = $stmt_marks->get_result();

$marks = [];
while ($row = $result_marks->fetch_assoc()) {
    $marks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Marks</title>
    <style>
        /* General Page Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #a72222;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #f9f9f9;
            border-radius: 4px;
            overflow: hidden;
        }

        thead {
            background-color: #a72222;
            color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 14px;
            color: #555;
        }

        td:first-child {
            font-weight: bold;
        }

        th {
            font-size: 16px;
            text-transform: capitalize;
        }

        tbody td[colspan] {
            font-size: 16px;
            color: #999;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div id="header-placeholder"></div>
    <script>
        // Load the header content from header.php
        fetch('header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>

    <div class="container">
        <h2>My Marks</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Semester</th>
                    <th>Marks Obtained</th>
                    <th>Maximum Marks</th>
                    <th>Entry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($marks) > 0): ?>
                    <?php foreach ($marks as $mark): ?>
                        <tr>
                            <td><?= htmlspecialchars($mark['subject_name']); ?></td>
                            <td><?= htmlspecialchars($mark['semester']); ?></td>
                            <td><?= htmlspecialchars($mark['marks']); ?></td>
                            <td><?= htmlspecialchars($mark['max_marks']); ?></td>
                            <td><?= htmlspecialchars($mark['entry_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No marks available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
