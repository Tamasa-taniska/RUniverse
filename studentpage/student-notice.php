<?php
// Database connection
// $host = "localhost";
// $username = "root"; // change if needed
// $password = "";     // change if needed
// $dbname = "notice"; // your DB name
include("dbconnect.php"); // Your DB connection file


// $data = new mysqli($host, $username, $password, $dbname);
// if ($data->connect_error) {
//     die("Connection failed: " . $data->connect_error);
// }

// Fetch all notices for 'students'
$sql = "SELECT notice_text, created_at FROM notice WHERE user_type = 'students' ORDER BY created_at DESC";
$result = $data->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Notices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .notice-board {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .notice {
            padding: 15px;
            margin-bottom: 15px;
            border-left: 5px solid #28A745;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .timestamp {
            font-size: 0.85em;
            color: #555;
            text-align: right;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>

<script>
    // Load the header content from header.html
    fetch('header.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        });
</script>

    <h1>Notices for Students</h1>

    <div class="notice-board">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="notice">';
                echo '<p>' . nl2br(htmlspecialchars($row["notice_text"])) . '</p>';
                echo '<div class="timestamp">' . date("d M Y, h:i A", strtotime($row["created_at"])) . '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No notices available for students.</p>";
        }

        $data->close();
        ?>
    </div>

</body>
</html>
