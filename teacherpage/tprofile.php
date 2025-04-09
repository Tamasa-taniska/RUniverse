<?php
session_start();
include("db_connect.php");

// Check if the user is logged in as faculty
if (!isset($_SESSION['faculty'])) {
    header("Location: index1.php"); // Redirect to login if not logged in
    exit();
}

// Get faculty info from session
$faculty = $_SESSION['faculty'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Profile</title>
    <link rel="stylesheet" href="tprofile.css">
</head>
<body>
<?php include("theader.php"); ?>

<div class="container">
    <div class="profile-photo">
        <img src="profile.jpg" alt="Faculty Photo" id="profile-photo">
        <input type="file" id="upload" name="photo" accept="image/*" onchange="updatePhoto()" style="margin-top: 10px;">
    </div>

    <div class="profile-infos">
        <h2>Faculty Details</h2>
        <div class="profile-info"><b>Faculty ID:</b> <?php echo $faculty['faculty_id']; ?></div>
        <div class="profile-info"><b>Name:</b> <?php echo $faculty['name']; ?></div>
        <div class="profile-info"><b>Mobile Number:</b> <?php echo $faculty['phone_number']; ?></div>
        <div class="profile-info"><b>Date of Birth:</b> <?php echo $faculty['DOB']; ?></div>
        <div class="profile-info"><b>Email:</b> <?php echo $faculty['email']; ?></div>
        <div class="profile-info"><b>Position:</b> <?php echo $faculty['designation']; ?></div>
        <div class="profile-info"><b>Address:</b> <?php echo $faculty['address']; ?></div>
        <div class="profile-info"><b>District:</b> <?php echo $faculty['district']; ?></div>
        <div class="profile-info"><b>State:</b> <?php echo $faculty['state']; ?></div>
        <div class="profile-info"><b>Pincode:</b> <?php echo $faculty['pincode']; ?></div>

        <button class="edit-button" onclick="editProfile()">Edit Profile</button>
    </div>
</div>

<!-- Edit Form -->
<div id="edit-form" style="display: none;">
    <div class="container">
        <div class="profile-photo">
            <img src="profile.jpg" alt="Faculty Photo" id="edit-photo" class="profile-photo">
        </div>
        <div class="profile-infos">
            <h3>Edit Profile</h3>
            <div class="edit-form">
                <label>Name:</label>
                <input type="text" id="edit-name" value="<?php echo $faculty['name']; ?>">
            </div>
            <div class="edit-form">
                <label>Mobile Number:</label>
                <input type="text" id="edit-mobile" value="<?php echo $faculty['phone_number']; ?>">
            </div>
            <div class="edit-form">
                <label>Date of Birth:</label>
                <input type="text" id="edit-DOB" value="<?php echo $faculty['DOB']; ?>">
            </div>
            <div class="edit-form">
                <label>Email:</label>
                <input type="email" id="edit-email" value="<?php echo $faculty['email']; ?>">
            </div>
            <div class="edit-form">
                <label>Position:</label>
                <input type="text" id="edit-position" value="<?php echo $faculty['designation']; ?>">
            </div>
            <div class="edit-form">
                <label>Address:</label>
                <textarea id="edit-address"><?php echo $faculty['address']; ?></textarea>
            </div>
            <div class="edit-form">
                <label>District:</label>
                <input type="text" id="edit-district" value="<?php echo $faculty['district']; ?>">
            </div>
            <div class="edit-form">
                <label>State:</label>
                <input type="text" id="edit-state" value="<?php echo $faculty['state']; ?>">
            </div>
            <div class="edit-form">
                <label>Pincode:</label>
                <input type="text" id="edit-pincode" value="<?php echo $faculty['pincode']; ?>">
            </div>

            <button class="save-button">Save Changes</button>
            <button class="cancel-button" onclick="cancelEdit()">Cancel</button>
        </div>
    </div>
</div>
<p style="text-align: right; margin: 10px;">
    <a href="logout.php" style="text-decoration: none; color: #fff; background-color: #f44336; padding: 5px 10px; border-radius: 5px;">Logout</a>
</p>

</body>
</html>
