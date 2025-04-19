<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';

$email = $_SESSION['email'];

// Fetch user details from `users` table
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.role, u.State, u.City, u.pincode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               f.designation, f.email, f.DOB, f.phone_number, f.photo
        FROM users u 
        LEFT JOIN faculty f ON u.user_id = f.faculty_id
        WHERE f.email = ?";
        
$stmt = $data->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$info = $result->fetch_assoc();
$username= $info["first_name"];
$userid= $info["user_id"];
// $imgpath= 'profile'.$userid;

$imgpath = "uploads/default_photo.jpg"; // Default image path
$photoCheck = $data->query("SELECT photo FROM faculty WHERE faculty_id = '$userid'");
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
    die("Error: No faculty data found for this email.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Profile</title>
    <link rel="stylesheet" href="tprofile.css">
</head>
<body>
<div id="header-placeholder"></div>
    <script>
        // Load the header content from header.html
        fetch('theader.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('header-placeholder').innerHTML = data;
        
        // Re-select logoutButton after header is loaded
        const logoutButton = document.getElementById("logoutButton");
        if (logoutButton) {
            logoutButton.addEventListener("click", function () {
                window.location.href = "/ravenshaw/studentpage/logout.php";
            });
            console.log("Logout button event listener added.");
        } else {
            console.error("Logout button not found!");
        }
    });

<div class="container">
    <div class="profile-photo">
        <img src="profile.jpg" alt="Faculty Photo" id="profile-photo">
        <input type="file" id="upload" name="photo" accept="image/*" onchange="updatePhoto()" style="margin-top: 10px;">
    </div>

    <div class="profile-infos">
        <h2>Faculty Details</h2>
        <div class="profile-info"><b>Faculty ID:</b> <?php echo htmlspecialchars($info['faculty_id']); ?></div>
        <div class="profile-info"><b>Name:</b> <?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?></div>
        <div class="profile-info"><b>Mobile Number:</b> <?php echo htmlspecialchars($info['phone_number'] ?? 'Not Available'); ?></div>
        <div class="profile-info"><b>Date of Birth:</b> <?php echo htmlspecialchars($info['DOB']); ?></div>
        <div class="profile-info"><b>Email:</b> <?php echo htmlspecialchars($info['email']); ?></div>
        <div class="profile-info"><b>Position:</b> <?php echo htmlspecialchars($info['designation']); ?></div>
        <div class="profile-info"><b>Address:</b> <?php echo htmlspecialchars($info['House_No_Building_Name']); ?></div>
        <div class="profile-info"><b>District:</b> <?php echo htmlspecialchars($info['City']); ?></div>
        <div class="profile-info"><b>State:</b> <?php echo htmlspecialchars($info['State']); ?></div>
        <div class="profile-info"><b>Pincode:</b> <?php echo htmlspecialchars($info['pincode']); ?></div>

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
                <input type="text" id="edit-name" value="<?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?>"> 
            </div>
            <div class="edit-form">
                <label>Mobile Number:</label>
                <input type="text" id="edit-mobile" value="<?php echo htmlspecialchars($info['phone_number']); ?>">
            </div>
            <div class="edit-form">
                <label>Date of Birth:</label>
                <input type="text" id="edit-DOB" value="<?php echo htmlspecialchars($info['DOB']); ?>">
            </div>
            <div class="edit-form">
                <label>Email:</label>
                <input type="email" id="edit-email" value="<?php echo htmlspecialchars($info['email']); ?>">
            </div>
            <div class="edit-form">
                <label>Position:</label>
                <input type="text" id="edit-position" value="<?php echo htmlspecialchars($info['designation']); ?>">
            </div>
            <div class="edit-form">
                <label>Address:</label>
                <textarea id="edit-address"><?php echo htmlspecialchars($info['House_No_Building_Name']); ?></textarea>
            </div>
            <div class="edit-form">
                <label>District:</label>
                <input type="text" id="edit-district" value="<?php echo htmlspecialchars($info['City']); ?>">
            </div>
            <div class="edit-form">
                <label>State:</label>
                <input type="text" id="edit-state" value="<?php echo htmlspecialchars($info['State']); ?>">
            </div>
            <div class="edit-form">
                <label>Pincode:</label>
                <input type="text" id="edit-pincode" value="<?php echo htmlspecialchars($info['pincode']); ?>">
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
