<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    session_destroy();
    header("location: ../studentpage/login.php");
    exit();
}
// include 'db_connection.php';
include("../studentpage/dbconnect.php");

$email = $_SESSION['email'];

// Fetch admin details from `admin` and `users` tables
$sql = "SELECT u.user_id, u.first_name, u.last_name, u.role, u.State, u.City, u.pincode, u.House_No_Building_Name, u.Road_Name_Area_Colony, 
               a.admin_id, a.email, a.photo
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

$username = $info["first_name"];
$userid = $info["user_id"];

$imgpath = "/ravenshaw/adminpage/uploads/default_photo.jpg"; // Default image path
$photoCheck = $data->query("SELECT photo FROM admin WHERE admin_id = '$userid'");
$photoRow = $photoCheck->fetch_assoc();

if ($photoRow['photo'] == 1) {
    $jpgPath = "/ravenshaw/adminpage/uploads/profile" . $userid . ".jpg";
    $pngPath = "/ravenshaw/adminpage/uploads/profile" . $userid . ".png";
    
    $jpgFullPath = $_SERVER['DOCUMENT_ROOT'] . $jpgPath;
    $pngFullPath = $_SERVER['DOCUMENT_ROOT'] . $pngPath;

    // The file_exists() method requires a server path and the above is in form of web path. Hence
    // we have used $_SERVER['DOCUMENT_ROOT'] to convert web to server path.

    if (file_exists($jpgFullPath)) {
        $imgpath = $jpgPath;
    } elseif (file_exists($pngFullPath)) {
        $imgpath = $pngPath;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="a_profile.css">
</head>
<body>
    <div id="header-placeholder"></div>
    <script>
        // Load the header content from header.php
        fetch('a_header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>
    <div class="profile-container">
        <h2>Admin Details</h2>
        <div class="admin-image">
            <!-- <img src="<?php //echo './uploads/' . $imgpath . '.jpg'; ?>" alt="Student Image" class="student-photo"> -->
            <img src="<?php echo htmlspecialchars($imgpath); ?>" alt="Admin Image" class="admin-photo">
        </div>
        <div class="admin-details">
            <div class="column">
                <p id="adminName"><b>Admin Name:</b> <?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?></p>
                <p id="adminEmail"><b>Email:</b> <?php echo htmlspecialchars($info['email']); ?></p>
                <p id="adminRole"><b>Role:</b> <?php echo htmlspecialchars($info['role']); ?></p>
                <p id="state"><b>State:</b> <?php echo htmlspecialchars($info['State']); ?></p>
                <p id="city"><b>City:</b> <?php echo htmlspecialchars($info['City']); ?></p>
            </div>
            <div class="column">
                <p id="userId"><b>User ID:</b> <?php echo htmlspecialchars($userid); ?></p>
                <p id="pinCode"><b>Pin Code:</b> <?php echo htmlspecialchars($info['pincode']); ?></p>
                <p id="houseNo"><b>House No/Building Name:</b> <?php echo htmlspecialchars($info['House_No_Building_Name']); ?></p>
                <p id="roadName"><b>Road Name/Area/Colony:</b> <?php echo htmlspecialchars($info['Road_Name_Area_Colony']); ?></p>
            </div>
        </div>
        <div class="edit-button">
            <!-- <form method="POST" action="editAdminProfile.php">
                <input type="hidden" name="userId" value="22DIT125">
                <button type="submit" style="border: none; background: none; padding: 0;">
                    <img src="/ravenshaw/adminpage/assets/pencil.png" alt="Edit Profile">
                </button>
            </form> -->
            <form method="POST" action="/ravenshaw/adminpage/admin/editAdminProfile.php">
        <input type="hidden" name="rollNo" value="22DIT125">
        <button type="submit" style="border: none; background: none; padding: 0;">
            <img src="/ravenshaw/adminpage/assets/pencil.png" alt="Edit Profile">
        </button>
    </form>
        </div>
    </div>
</body>
</html>