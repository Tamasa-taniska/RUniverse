<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Student") {
    session_destroy();
    header("location: login.php");
    exit();
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
        fetch('header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;

            // Attach logout event listener after header is loaded
            document.getElementById("logoutButton").addEventListener("click", function () {
                window.location.href = "logout.php"; // Redirects to logout.php on click
            });
        });
    </script>
    <div class="profile-container">
        <h2>Student Details</h2>
        <div class="student-image">
            <img src="bg.jpeg" alt="Student Image" class="student-photo">
        </div>
        <div class="student-details">
            <div class="column">
                <p id="studentName">Student Name: John Doe</p>
                <p id="dateOfBirth">Date of Birth: 01/01/2000</p>
                <p id="address">Address: 123 Main St</p>
                <p id="city">City: Cuttack</p>
                <p id="state">State: Odisha</p>
                <p id="mobile">Mobile: 9876543210</p>
                <p id="course">Course: B.Tech</p>
                <p id="branch">Branch: CSE</p>
                <p id="bloodGroup">Blood Group: O+</p>
            </div>
            <div class="column">
                <p id="rollNo">Roll No: 22DIT125</p>
                <p id="motherName">Mother Name: Jane Doe</p>
                <p id="district">District: Cuttack</p>
                <p id="pinCode">Pin Code: 753001</p>
                <p id="email">EmailID: johndoe@example.com</p>
            </div>
        </div>
        <div class="edit-button" onclick="editProfile()">
            <img src="pencil.png" alt="Edit Profile">
        </div>
        
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditProfileModal()">&times;</span>
            <h2>Edit Profile</h2>
            <form id="editProfileForm">
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