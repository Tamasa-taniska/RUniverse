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
               f.designation, f.email, f.DOB, f.phone_number, f.photo, f.faculty_id
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        body{
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.logo-container {
    width: 100%;
    height: 130px;
    background-color: #f2f2f2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo {
    height: 130px;
    width: 100%;
    object-fit: cover;
}

.info-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px;
    background-color: #f1efef;
    border-top: 1px solid #f1eeee;
}

.student-info {
    display: flex;
    gap: 10px;
    font-size: 15px;
}

.actions {
    display: flex;
    gap: 10px;
}

button {
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    background-color: rgb(177, 19, 19);
    color: white;
    border-radius: 5px;
}

button:hover {
    background-color: rgb(177, 19, 19);
} 

/* Navbar Styles */
.navbar {
    background-color: rgb(177, 19, 19);
}

.navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar li {
    position: relative; 
}

.navbar a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 20px;
    text-decoration: none;
}
.navbar a:hover {
    background-color: rgb(139, 135, 135);
}

/* Dropdown Menu Styles */
.dropdown .dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #333;
    z-index: 1;
}

.dropdown:hover .dropdown-content {
    display: block; 
}

/* Style for dropdown links */
.dropdown-content a {
    color: whitesmoke;
    text-decoration: none;
    display: block;
    text-align: left;
}

.dropdown-content a:hover {
    background-color: #575757;
}

    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="/ravenshaw/studentpage/logo.jpeg" alt="Logo" class="logo">
        </div>
        <div class="info-container">
        <div class="student-info">
           <p>Name: <?php echo htmlspecialchars($info['first_name'] . " " . $info['last_name']); ?></p>
           <p>Teacher ID: <?php echo htmlspecialchars($info['faculty_id']); ?></p>
       </div>

            <div class="actions">
                <form method="POST" action="../studentpage/logout.php">
                    <button type="submit" id="logoutButton">Logout</button>
                </form>
            </div>
        </div> 
        <nav class="navbar">
            <ul>
                <li><a href="tprofile.php">Profile</a></li>
                <li class="dropdown">
                    <a href="#">Messages</a>
                    <ul class="dropdown-content">
                        <li><a href="t_inbox.php">Inbox</a></li>
                        <li><a href="tcompose.php">Compose</a></li>
                        <li><a href="tnotice.php">Notice</a></li>
                    </ul>
                </li>
                <li><a href="internal.php">Scorecard</a></li>
                <li class="dropdown">
                    <a href="#">Courses</a>
                    <ul class="dropdown-content">
                        <li><a href="subject_assigned.php">Subjects Assigned</a></li>
                        <li><a href="StudyMaterial.php">Study materials</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Student</a>
                    <ul class="dropdown-content">
                        <li><a href="#">Performance Tracking</a></li>
                        <li><a href="#">Assignment & Examination</a></li>
                    </ul>
                </li>
                <li><a href="#">Leave Management</a></li>
                <li><a href="#">Publication</a></li>
                <li><a href="#">Circular</a></li>
            </ul>
        </nav>
        
    </header>
</body>
</html>
