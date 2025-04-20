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

// Fetch user details from `users` table
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.role, u.State, u.City, u.pincode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               s.roll_number, s.email, s.dob, s.status, s.phone_number, s.blood_group, s.semester, s.photo
        FROM users u 
        LEFT JOIN students s ON u.user_id = s.student_id
        WHERE s.email = ?";

$stmt = $data->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$info = $result->fetch_assoc();

if (!$info) {
    die("Error: No student data found for this email.");
}

// Store semester ID in session
$_SESSION['semester_id'] = $info['semester'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student's Inbox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f7ef;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #bc0a0a;
        }

        .message-item {
            background-color: #f9fff7;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .message-item p {
            margin: 5px 0;
            color: #333;
        }

        small {
            color: #888;
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
        <h1>Inbox</h1>
        <div id="messages"></div>
    </div>
    <script>
        // Automatically fetch messages using the semester ID from the session
        document.addEventListener("DOMContentLoaded", function() {
            const semester_id = <?php echo json_encode($_SESSION['semester_id']); ?>; // Get semester ID from session

            if (semester_id) {
                fetchMessages(semester_id);
            } else {
                document.getElementById("messages").innerHTML = "<p>No semester ID found.</p>";
            }
        });

        function fetchMessages(semester_id) {
            fetch(`fetch.php?semester_id=${semester_id}`)
                .then(response => response.json())
                .then(data => {
                    const messagesDiv = document.getElementById("messages");
                    messagesDiv.innerHTML = ""; // Clear previous messages

                    if (data.length > 0) {
                        data.forEach(msg => {
                            const messageElement = document.createElement("div");
                            messageElement.className = "message-item";
                            messageElement.innerHTML = `
                                <p><strong>Message:</strong> ${msg.message}</p>
                                <p><strong>Teacher:</strong> ${msg.teacher_name}</p>
                                <small>${msg.timestamp}</small>
                            `;
                            messagesDiv.appendChild(messageElement);
                        });
                    } else {
                        messagesDiv.innerHTML = "<p>No messages found for this semester.</p>";
                    }
                })
                .catch(error => console.error("Error fetching messages:", error));
        }
    </script>
</body>
</html>