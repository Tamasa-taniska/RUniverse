<?php
// include 'db_connection.php';
include("../studentpage/dbconnect.php");
session_start();

// Check if the email session variable is set
if (!isset($_SESSION['email'])) {
    // Handle the case where the user is not logged in
    die("Error: User is not logged in.");
}

$email = $_SESSION['email'];

$sql = "SELECT u.first_name, u.middle_name, u.last_name, a.admin_id, a.email
        FROM users u 
        JOIN admin a ON u.user_id = a.admin_id
        WHERE a.email = ?";

$stmt = $data->prepare($sql);
if (!$stmt) {
    die("SQL prepare failed: " . $data->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$info = $result->fetch_assoc();

if (!$info) {
    die("Error: No admin data found for this email.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- <base href="/adminpage/"> -->
    <link rel="stylesheet" href="/ravenshaw/adminpage/admin.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="/ravenshaw/adminpage/assets/logo.jpeg" alt="Logo" class="logo">
        </div>
        <div class="info-container">
            <div class="admin-info">
                <p id="adminName"><b>Name:</b> <?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?></p>
                <p id="adminEmail"><b>Email:</b> <?php echo htmlspecialchars($info['email']); ?></p>
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
                <li><a href="/ravenshaw/adminpage/adminhome.php">Profile</a></li>
                <li><a href="/ravenshaw/adminpage/studentAction.html">Student Management</a></li>
                <li><a href="/ravenshaw/adminpage/faculty_management.html">Faculty Management</a></li>
                <!-- <li><a href="/ravenshaw/alumni/admin/index.php">Alumni Management</a></li> -->
                <li><a href="/ravenshaw/adminpage/notice_sender.php">Notice Management</a></li>
                <li><a href="/ravenshaw/adminpage/admin/upload-marks.php">Publish Results</a></li>
                <li><a href="/ravenshaw/adminpage/admin_registration.php">Add Admin</a></li>
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