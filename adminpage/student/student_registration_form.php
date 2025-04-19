<?php
require '../../studentpage/dbconnect.php';

// Fetch courses from DB
$courses = [];
$sql = "SELECT course_id, course_name FROM courses";
$result = $data->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration Form</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="header-placeholder"></div>
    <script>
        // Load the header content from header.php
        fetch('../a_header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>
  <div class="container">
    <h1>Student Registration Form</h1>
    <form id="registrationForm" action="register.php" method="POST">
      <h2>Personal Information</h2>
      <input type="text" name="first_name" placeholder="First Name" required>
      <input type="text" name="middle_name" placeholder="Middle Name">
      <input type="text" name="last_name" placeholder="Last Name" required>

      <h2>Address Information</h2>
      <input type="text" name="pin_code" placeholder="Pin Code" pattern="\d{6}" required>
      <input type="text" name="state" placeholder="State" required>
      <input type="text" name="city" placeholder="City" required>
      <input type="text" name="house_number" placeholder="House No./Building Name" required>
      <input type="text" name="road_name" placeholder="Road Name/Area/Colony" required>

      <h2>University Information</h2>
      <input type="text" name="batch" placeholder="Batch Number" required>
      <input type="text" name="roll_number" placeholder="Roll Number" required>
      <label for="dob">Date of Birth:</label>
      <input type="date" name="dob" required>

      <!-- Department Dropdown (Course Name) -->
      <select name="department" required>
        <option value="">Select Department</option>
        <?php foreach ($courses as $course): ?>
          <option value="<?php echo htmlspecialchars($course['course_name']); ?>">
            <?php echo htmlspecialchars($course['course_name']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Course ID Dropdown -->
      <select name="course_id" required>
        <option value="">Select Course ID</option>
        <?php foreach ($courses as $course): ?>
          <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
            <?php echo $course['course_id'] . " - " . htmlspecialchars($course['course_name']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Semester Dropdown -->
      <select name="semester" required>
        <option value="">Select Semester</option>
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
      </select>

      <h2>Contact Information</h2>
      <input type="text" name="phone_number" placeholder="Phone Number" pattern="\d{10}" required>
      <input type="text" name="blood_group" placeholder="Blood Group" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
