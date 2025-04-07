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
$sql = "SELECT u.first_name, u.last_name, u.role, u.State, u.City, u.pincode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               s.roll_number,s.email, s.dob, s.status, s.phone_number, s.blood_group
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
            <img src="bg.jpeg" alt="Student Image" class="student-photo">
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


     <!-- Edit Profile Modal -->
<div id="editProfileModal" class="edit-modal">
    <div class="edit-modal-content">
        <span class="close">&times;</span>
        <h2>Edit Profile</h2>
        <form action="editProfile.php" method="POST">
            <div class="form-group">
                <label for="editStudentName">Student Name</label>
                <input type="text" id="editStudentName" name="studentName" value="John Doe" required>
            </div>
            <div class="form-group">
                <label for="editDateOfBirth">Date of Birth</label>
                <input type="date" id="editDateOfBirth" name="dateOfBirth" value="2000-01-01" required>
            </div>
            <div class="form-group">
                <label for="editAddress">Address</label>
                <input type="text" id="editAddress" name="address" value="123 Main St" required>
            </div>
            <div class="form-group">
                <label for="editCity">City</label>
                <input type="text" id="editCity" name="city" value="Cuttack" required>
            </div>
            <div class="form-group">
                <label for="editState">State</label>
                <input type="text" id="editState" name="state" value="Odisha" required>
            </div>
            <div class="form-group">
                <label for="editMobile">Mobile</label>
                <input type="text" id="editMobile" name="mobile" value="9876543210" required>
            </div>
            <div class="form-group">
                <label for="editCourse">Course</label>
                <input type="text" id="editCourse" name="course" value="B.Tech" required>
            </div>
            <div class="form-group">
                <label for="editBranch">Branch</label>
                <input type="text" id="editBranch" name="branch" value="CSE" required>
            </div>
            <div class="form-group">
                <label for="editBloodGroup">Blood Group</label>
                <input type="text" id="editBloodGroup" name="bloodGroup" value="O+" required>
            </div>
            <div class="form-group">
                <label for="editRollNo">Roll No</label>
                <input type="text" id="editRollNo" name="rollNo" value="22DIT125" required>
            </div>
            <div class="form-group">
                <label for="editMotherName">Mother Name</label>
                <input type="text" id="editMotherName" name="motherName" value="Jane Doe" required>
            </div>
            <div class="form-group">
                <label for="editDistrict">District</label>
                <input type="text" id="editDistrict" name="district" value="Cuttack" required>
            </div>
            <div class="form-group">
                <label for="editPinCode">Pin Code</label>
                <input type="text" id="editPinCode" name="pinCode" value="753001" required>
            </div>
            <div class="form-group">
                <label for="editEmail">EmailID</label>
                <input type="email" id="editEmail" name="email" value="johndoe@example.com" required>
            </div>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

    <script src="scripts.js"></script>
</body>
</html>