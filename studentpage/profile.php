<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "students") {
    session_destroy();
    header("location: login.php");
    exit();
}
include 'dbconnect.php';

$email = $_SESSION['email'];

// Fetch user details from `users` table
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.role, u.State, u.City, u.pincode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               s.roll_number,s.email, s.dob, s.status, s.phone_number, s.blood_group
        FROM users u 
        LEFT JOIN students s ON u.user_id = s.student_id
        WHERE s.email = ?";
        
$stmt = $data->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$info = $result->fetch_assoc();
$username= $info["first_name"];
$userid= $info["user_id"];
// $imgpath= 'profile'.$userid;

$imgpath = "uploads/default_photo.jpg"; // Default image path
$photoCheck = $data->query("SELECT photo FROM students WHERE student_id = '$userid'");
$photoRow = $photoCheck->fetch_assoc();

if ($photoRow['photo'] == 1) {
    $jpgPath = "uploads/profile" . $userid . ".jpg";
    $pngPath = "uploads/profile" . $userid . ".png";
    
    if (file_exists($jpgPath)) {
        $imgpath = $jpgPath;
    } elseif (file_exists($pngPath)) {
        $imgpath = $pngPath;
    }
}


if (!$info) {
    die("Error: No student data found for this email.");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="studentStyles.css">
</head>
<body>
    <div id="header-placeholder"></div>
    <script>
        // Load the header content from header.html
        fetch('header.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('header-placeholder').innerHTML = data;
        
        // Re-select logoutButton after header is loaded
        const logoutButton = document.getElementById("logoutButton");
        if (logoutButton) {
            logoutButton.addEventListener("click", function () {
                window.location.href = "logout.php";
            });
            console.log("Logout button event listener added.");
        } else {
            console.error("Logout button not found!");
        }
    });

    </script>
    <div class="profile-container">
        <h2>Student Details</h2>
        <div class="student-image">
            <!-- <img src="<?php //echo './uploads/' . $imgpath . '.jpg'; ?>" alt="Student Image" class="student-photo"> -->
            <img src="<?php echo htmlspecialchars($imgpath); ?>" alt="Student Image" class="student-photo">
        </div>
        <div class="student-details">
            <div class="column">

                <p id="studentName"><b>Student Name:</b> <?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?></p>
                <p id="dateOfBirth"><b>Date of Birth:</b> <?php echo htmlspecialchars($info['dob'] ?? 'Not Available'); ?></p>
                <p id="address"><b>House No/ Building Name:</b> <?php echo htmlspecialchars($info['House_No_Building_Name']); ?></p>
                <p id="city"><b>City:</b> <?php echo htmlspecialchars($info['City']); ?></p>
                <p id="state"><b>State:</b> <?php echo htmlspecialchars($info['State']); ?></p>
                <p id="mobile"><b>Mobile:</b> <?php echo htmlspecialchars($info['phone_number'] ?? 'Not Available'); ?></p>
                <!-- <p id="course">Course: B.Tech</p>
                <p id="branch">Branch: CSE</p> -->
                <p id="bloodGroup"><b>Blood Group:</b> <?php echo htmlspecialchars($info['blood_group'] ?? 'Not Available'); ?></p>

            </div>
            <div class="column">
                
                <p id="rollNo"><b>Roll No:</b> <?php echo htmlspecialchars($info['roll_number']); ?></p>
                <!-- <p id="motherName">Mother Name: Jane Doe</p> -->
                <!-- <p id="district">District: Cuttack</p> -->
                <p id="pinCode"><b>Pin Code:</b> <?php echo htmlspecialchars($info['pincode']); ?></p>
                <p id="email"><b>EmailID:</b> <?php echo htmlspecialchars($info['email']); ?></p>

            </div>
        </div>
        <div class="edit-button">
    <!-- Use a form to send the roll number -->
    <form method="POST" action="editProfile.php">
        <input type="hidden" name="rollNo" value="22DIT125">
        <button type="submit" style="border: none; background: none; padding: 0;">
            <img src="pencil.png" alt="Edit Profile">
        </button>
    </form>
</div>
    <!-- <script src="scripts.js"></script> -->
</body>
</html>