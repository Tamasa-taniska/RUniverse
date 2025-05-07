<?php
include 'dbconnect.php';
session_start();
$email = $_SESSION['email'];
$sql = "SELECT u.first_name, u.last_name, s.roll_number
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <!-- <link rel="stylesheet" href="header.css"> -->
    <link rel="stylesheet" href="/ravenshaw/studentpage/header.css">

</head>
<body>
    <header>
        <div class="logo-container">
            <img src="/ravenshaw/studentpage/logo.jpeg" alt="Logo" class="logo">
        </div>
        <div class="info-container">
            <div class="student-info">
                <!-- <p>Name: </p> -->
                <p id="studentName"><b>Name:</b> <?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?></p>
                <!-- <p>Roll Number: </p> -->
                <p id="rollNo"><b>Roll Number:</b> <?php echo htmlspecialchars($info['roll_number']); ?></p>
            </div>
            <div class="actions">
                <form method="POST" action="/ravenshaw/studentpage/logout.php">
                    <button type="submit" id="logoutButton">Logout</button>
                </form>
                <!-- <button id="changePassword" onclick="openChangePasswordModal()">Change Password</button> -->
            </div>
        </div> 
        <nav class="navbar">
            <ul>
                <li><a href="/ravenshaw/studentpage/profile.php">Profile</a></li>
                <li><a href="/ravenshaw/teacherpage/view_notes.php">Notes</a></li>
                <li><a href="/ravenshaw/studentpage/inbox.php">Inbox</a></li>
                <li><a href="/ravenshaw/studentpage/compose.php">Compose</a></li>
                <li class="dropdown">
                    <a href="#">Scorecard</a>
                    <ul class="dropdown-content">
                        <li><a href="/ravenshaw/studentpage/internal_marks.php">Internal</a></li>
                        <li><a href="/ravenshaw/adminpage/student/view-marks.php">Semester</a></li>
                    </ul>
                </li>
                <!-- <li><a href="notice.html">Notice</a></li> -->
                <li><a href="/ravenshaw/studentpage/student-notice.php">Notice</a></li>
                <li class="dropdown">
                    <a href="#">Examination</a>
                    <ul class="dropdown-content">
                        <li><a href="#">Form Fill-Up</a></li>
                        <li><a href="#">Hall Ticket</a></li>
                        <li><a href="#">Xerox</a></li>
                        <li><a href="#">Recheck</a></li>
                    </ul>
                </li>
                <li><a href="/ravenshaw/studentpage/anti-ragging.html">Anti Ragging Cell</a></li>
                <li><a href="/ravenshaw/studentpage/self_assesment/counsellor.html">Talk to Counsellor</a></li>
            </ul>
        </nav>

        <!-- Change Password Modal -->
        <div id="changePasswordModal" style="display:none;">
            <form method="POST" action="change_password.php">
                <h2>Change Password</h2>
                <input type="password" name="current_password" placeholder="Current Password" required><br>
                <input type="password" name="new_password" placeholder="New Password" required><br>
                <button type="submit">Submit</button>
                <button type="button" onclick="closeChangePasswordModal()">Cancel</button>
            </form>
        </div>

        <script>
            function openChangePasswordModal() {
                document.getElementById('changePasswordModal').style.display = 'block';
            }

            function closeChangePasswordModal() {
                document.getElementById('changePasswordModal').style.display = 'none';
            }
        </script>
    </header>
</body>
</html>
