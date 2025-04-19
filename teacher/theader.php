
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['faculty'])) {
    header("Location: index1.php");
    exit();
}
$faculty = $_SESSION['faculty'];
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
            <img src="logo.jpeg" alt="Logo" class="logo">
        </div>
        <div class="info-container">
        <div class="student-info">
           <p>Name: <?php echo htmlspecialchars($faculty['name']); ?></p>
           <p>Teacher ID: <?php echo htmlspecialchars($faculty['faculty_id']); ?></p>
       </div>

            <div class="actions">
                <form method="POST" action="logout.php">
                    <button type="submit" id="logoutButton">Logout</button>
                </form>
            </div>
        </div> 
        <nav class="navbar">
            <ul>
                <li><a href="tprofile.html">Profile</a></li>
                <li class="dropdown">
                    <a href="#">Messages</a>
                    <ul class="dropdown-content">
                        <li><a href="t_inbox.php">Inbox</a></li>
                        <li><a href="tcompose.php" onclick="compose()">Compose</a></li>
                        <li><a href="tnotice.html">Notice</a></li>
                    </ul>
                </li>
                <li><a href="tinternal.php">Scorecard</a></li>
                <li class="dropdown">
                    <a href="#">Courses</a>
                    <ul class="dropdown-content">
                        <li><a href="subject_assigned.php">Subject assigned</a></li>
                        <li><a href="StudyMaterials.php" onclick="studymaterials()">Study materials</a></li>
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

