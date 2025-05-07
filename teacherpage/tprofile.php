<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';

$email = $_SESSION['email'];

$sql = "SELECT u.user_id, u.first_name, u.last_name, u.role, u.State, u.City, u.pincode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               f.designation, f.email, f.DOB, f.phone_number, f.photo, f.faculty_id
        FROM users u 
        LEFT JOIN faculty f ON u.user_id = f.faculty_id
        WHERE f.email = ?";

$stmt = $data->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$info = $result->fetch_assoc();
$username = $info["first_name"];
$userid = $info["user_id"];

$imgpath = "uploads/default_photo.jpg";
$jpgPath = "uploads/profile" . $userid . ".jpg";
$pngPath = "uploads/profile" . $userid . ".png";

if ($info['photo'] == 1) {
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
    <style>
        .profile-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('theader.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;

            const logoutButton = document.getElementById("logoutButton");
            if (logoutButton) {
                logoutButton.addEventListener("click", function () {
                    window.location.href = "/ravenshaw/studentpage/logout.php";
                });
            }
        })
        .catch(error => {
            console.error('Error loading header:', error);
        });
</script>

<div class="container">
    <div class="profile-photo">
        <img src="<?php echo $imgpath; ?>" alt="Faculty Photo" id="profile-photo">
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

<div id="edit-form" style="display: none;">
<form action="tupdate_profile.php?faculty_id=<?php echo $info['faculty_id']; ?>" method="POST" enctype="multipart/form-data">
    <div class="edit-form">
        <label>Mobile Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($info['phone_number']); ?>">
    </div>
    <div class="edit-form">
        <label>Address:</label>
        <textarea name="House_No_Building_Name"><?php echo htmlspecialchars($info['House_No_Building_Name']); ?></textarea>
    </div>
    <div class="edit-form">
        <label>Pincode:</label>
        <input type="text" name="pincode" value="<?php echo htmlspecialchars($info['pincode']); ?>">
    </div>
    <div class="edit-form">
        <label>District:</label>
        <input type="text" name="City" value="<?php echo htmlspecialchars($info['City']); ?>">
    </div>
    <div class="edit-form">
        <label>State:</label>
        <input type="text" name="State" value="<?php echo htmlspecialchars($info['State']); ?>">
    </div>
    <div class="edit-form">
        <label>Change Photo:</label>
        <input type="file" name="photo" accept="image/*">
    </div>

    <button class="save-button" type="submit">Save Changes</button>
    <button class="cancel-button" type="button" onclick="cancelEdit()">Cancel</button>
</form>
</div>

<script>
function editProfile() {
    document.getElementById("edit-form").style.display = "block";
    document.querySelector(".container").style.display = "none";
}

function cancelEdit() {
    document.getElementById("edit-form").style.display = "none";
    document.querySelector(".container").style.display = "flex";
}

function updatePhoto() {
    const fileInput = document.getElementById("upload");
    const photo = document.getElementById("profile-photo");

    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            photo.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

</body>
</html>
