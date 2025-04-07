<?php
// session_start();
// $host = "localhost";
// $user = "root";
// $password = "@ashu2003";
// $db = "runiverse";

// // Create database data$dataection
// $data = new mysqli($host, $user, $password, $db);

// // Check data$dataection
// if ($data->data$dataect_error) {
//     die("data$dataection failed: " . $data->data$dataect_error);
// }

// // Check if form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") 
// {
//     $name = $_POST['username']; //email id of user
//     $pass = $_POST['password']; //corresponding password for user

//     // Use prepared statement to prevent SQL injection
//     $sql = "SELECT * FROM users WHERE email = ?";
//     $stmt = $data->prepare($sql);
//     $stmt->bind_param("s", $name);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $row = $result->fetch_assoc();

//     // Verify password
//     if ($row && $row["password"] === $pass) 
//     { 
//         $_SESSION['email']= $row['email'];
//         $_SESSION['username'] = $name; //email of user
//         $_SESSION['role'] = $row["role"];

//         if ($row["role"] == "Student") {
//             header("location: profile.php");
//         } elseif ($row["role"] == "Admin") {
//             header("location: adminhome.html");
//         }
//         exit();
//     } else {
//         $_SESSION['loginMessage'] = "Username or password do not match";
//         header("location: login.php");
//         exit();
//     }
// }
?>

<?php
session_start();
$host = "localhost";
$user = "root";
$password = "@ashu2003";
$db = "runiverse";

// Create database connection
$data = new mysqli($host, $user, $password, $db);

// Check connection
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username']; // email ID of user
    $pass = $_POST['password']; // corresponding password for user
    $role = $_POST['USER']; // User type from dropdown

    // Determine the table based on selected user type
    $valid_roles = ["students", "faculty", "admin"];
    
    if (!in_array($role, $valid_roles)) {
        $_SESSION['loginMessage'] = "Invalid user role selected.";
        header("location: login.php");
        exit();
    }
    $table= $role;
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $data->prepare($sql);
    
    // if (!$stmt) {
    //     $_SESSION['loginMessage'] = "Database error: " . $data->error;
    //     header("location: login.php");
    //     exit();
    // }

    if ($stmt === false) {
        die("SQL Error: " . $data->error); // Fix: Handle SQL errors
    }

    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verify password
    if ($row && $row["password"] === $pass) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $name;
        $_SESSION['role'] = $role; // User role

        // Redirect based on role
        if ($role == "students") {
            header("location: profile.php");
        } elseif ($role == "admin") {
            header("location: adminhome.html");
        } elseif ($role == "faculty") {
            header("location: facultyhome.php");
        }
        exit();
    } else {
        $_SESSION['loginMessage'] = "Invalid email or password.";
        header("location: login.php");
        exit();
    }
}
?>
