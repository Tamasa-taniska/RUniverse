<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_profiles";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rollNo = $conn->real_escape_string($_POST['rollNo']);

    // Fetch existing data for the roll number
    $sql = "SELECT * FROM students WHERE rollNo = '$rollNo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Display the pre-filled edit form
        echo '
        <form action="saveProfile.php" method="POST">
            <input type="hidden" name="rollNo" value="' . htmlspecialchars($student['rollNo']) . '">
            <div class="form-group">
                <label for="editStudentName">Student Name</label>
                <input type="text" id="editStudentName" name="studentName" value="' . htmlspecialchars($student['studentName']) . '" required>
            </div>
            <div class="form-group">
                <label for="editDateOfBirth">Date of Birth</label>
                <input type="date" id="editDateOfBirth" name="dateOfBirth" value="' . htmlspecialchars($student['dateOfBirth']) . '" required>
            </div>
            <div class="form-group">
                <label for="editAddress">Address</label>
                <input type="text" id="editAddress" name="address" value="' . htmlspecialchars($student['address']) . '" required>
            </div>
            <div class="form-group">
                <label for="editCity">City</label>
                <input type="text" id="editCity" name="city" value="' . htmlspecialchars($student['city']) . '" required>
            </div>
            <div class="form-group">
                <label for="editState">State</label>
                <input type="text" id="editState" name="state" value="' . htmlspecialchars($student['state']) . '" required>
            </div>
            <div class="form-group">
                <label for="editMobile">Mobile</label>
                <input type="text" id="editMobile" name="mobile" value="' . htmlspecialchars($student['mobile']) . '" required>
            </div>
            <div class="form-group">
                <label for="editCourse">Course</label>
                <input type="text" id="editCourse" name="course" value="' . htmlspecialchars($student['course']) . '" required>
            </div>
            <div class="form-group">
                <label for="editBranch">Branch</label>
                <input type="text" id="editBranch" name="branch" value="' . htmlspecialchars($student['branch']) . '" required>
            </div>
            <div class="form-group">
                <label for="editBloodGroup">Blood Group</label>
                <input type="text" id="editBloodGroup" name="bloodGroup" value="' . htmlspecialchars($student['bloodGroup']) . '" required>
            </div>
            <div class="form-group">
                <label for="editMotherName">Mother Name</label>
                <input type="text" id="editMotherName" name="motherName" value="' . htmlspecialchars($student['motherName']) . '" required>
            </div>
            <div class="form-group">
                <label for="editDistrict">District</label>
                <input type="text" id="editDistrict" name="district" value="' . htmlspecialchars($student['district']) . '" required>
            </div>
            <div class="form-group">
                <label for="editPinCode">Pin Code</label>
                <input type="text" id="editPinCode" name="pinCode" value="' . htmlspecialchars($student['pinCode']) . '" required>
            </div>
            <div class="form-group">
                <label for="editEmail">EmailID</label>
                <input type="email" id="editEmail" name="email" value="' . htmlspecialchars($student['email']) . '" required>
            </div>
            <button type="submit">Save Changes</button>
        </form>';
    } else {
        echo "Student not found.";
    }
}

$conn->close();
?>