<?php
require '../../studentpage/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Faculty Registration Form</title>
  <link rel="stylesheet" href="../student/style.css">
</head>
<body>
<div id="header-placeholder"></div>
    <script>
        // Load the header content dynamically
        fetch('../a_header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>

  <div class="container">
    <h1>Faculty Registration Form</h1>
    <form id="registrationForm" action="faculty_register.php" method="POST">
      <h2>Personal Information</h2>
      <input type="text" name="first_name" placeholder="First Name" required>
      <input type="text" name="middle_name" placeholder="Middle Name">
      <input type="text" name="last_name" placeholder="Last Name" required>
      <label for="dob">Date of Birth:</label>
      <input type="date" name="dob" required>
      <input type="text" name="designation" placeholder="Designation" required>

      <h2>Address Information</h2>
      <input type="text" name="pincode" placeholder="Pincode" pattern="\d{6}" required>
      <input type="text" name="state" placeholder="State" required>
      <input type="text" name="city" placeholder="City" required>
      <input type="text" name="house_number" placeholder="House No./Building Name" required>
      <input type="text" name="road_name" placeholder="Road Name/Area/Colony" required>

      <h2>Contact Information</h2>
      <input type="text" name="phone_number" placeholder="Phone Number" pattern="\d{10}" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
