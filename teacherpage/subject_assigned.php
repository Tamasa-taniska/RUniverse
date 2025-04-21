<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';

$email = $_SESSION['email'];
$faculty_id = '';
$assignments = [];
$error = '';

// Fetch faculty_id based on email
try {
    $stmt = $data->prepare("SELECT faculty_id FROM faculty WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $faculty_id = $row['faculty_id'];
    } else {
        $error = "No faculty found for email: " . htmlspecialchars($email);
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Fetch assigned subjects along with course_name
if (!empty($faculty_id)) {
    try {
        $stmt = $data->prepare(
            "SELECT ca.course_id, c.course_name, ca.sem, ca.sub 
             FROM course_allotment ca
             JOIN courses c ON ca.course_id = c.course_id
             WHERE ca.faculty_id = ?
             ORDER BY ca.course_id, ca.sem"
        );
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }

        if (empty($assignments)) {
            $error = "No subjects assigned for Faculty ID: " . htmlspecialchars($faculty_id);
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Allotted Subjects</title>
    <style>
    /* General Page Styling */
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(to right, #f2f2f2, #d6e4f0);
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        background: #ffffff;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h1, h2 {
        text-align: center;
        color: #c0392b;
    }

    h2 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    /* Error Message Styling */
    .error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 16px;
    }

    th, td {
        text-align: left;
        padding: 10px;
    }

    th {
        background: #c0392b;
        color: white;
    }

    td {
        background: #f9f9f9;
    }

    tr:nth-child(even) td {
        background: #eef7ff;
    }

    tr:hover td {
        background: #d6e4f0;
    }

    /* Logout Button */
    #logoutButton {
        display: inline-block;
        padding: 10px 20px;
        background: #e74c3c;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #logoutButton:hover {
        background-color: #c0392b;
    }
</style>

</head>
<body>
<div id="header-placeholder"></div>
<script>
    // Load the header content from theader.php
    fetch('theader.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
            
            // Now look for the logout button INSIDE this then block
            const logoutButton = document.getElementById("logoutButton");
            if (logoutButton) {
                logoutButton.addEventListener("click", function () {
                    window.location.href = "/ravenshaw/studentpage/logout.php";
                });
                console.log("Logout button event listener added.");
            } else {
                console.error("Logout button not found!");
            }
        })
        .catch(error => {
            console.error('Error loading header:', error);
        });
</script>
    <div class="container">
        <h1>Faculty Subject Allotments</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($faculty_id) && empty($error)): ?>
            <h2>Assigned Subjects for Faculty ID: <?php echo htmlspecialchars($faculty_id); ?></h2>

            <table border="1">
    <thead>
        <tr>
            <th>Course</th>
            <th>Course Name</th>
            <th>Semester</th>
            <th>Subject</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?php echo htmlspecialchars(strtoupper($assignment['course_id'])); ?></td>
                <td><?php echo htmlspecialchars(ucwords($assignment['course_name'])); ?></td>
                <td><?php echo htmlspecialchars(strtoupper($assignment['sem'])); ?></td>
                <td><?php echo htmlspecialchars(ucwords($assignment['sub'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

        <?php endif; ?>
    </div>
</body>
</html>
