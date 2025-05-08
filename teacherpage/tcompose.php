<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}

include '../studentpage/dbconnect.php';

// Check if the user's designation is "HOD"
$hod_check_query = $data->prepare("SELECT designation FROM faculty WHERE email = ?");
$hod_check_query->bind_param("s", $_SESSION['email']);
$hod_check_query->execute();
$hod_check_result = $hod_check_query->get_result();
$hod_check = $hod_check_result->fetch_assoc();

// Corrected strpos check
if ($hod_check && strpos($hod_check['designation'], '(HOD)') !== false) {
    // Redirect HODs to compose-hod.php
    header("Location: compose-hod.php");
    exit();
}

// Get the HOD's email for faculty users
$hod_query = $data->prepare("SELECT email FROM faculty WHERE designation LIKE '%(HOD)%'");
$hod_query->execute();
$hod_result = $hod_query->get_result();
$hod = $hod_result->fetch_assoc();

if (!$hod) {
    die("HOD not found in the system.");
}

$hod_email = $hod['email']; // HOD's email
$error = "";
$success = "";

// Send message logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sender_email = $_SESSION['email'];

    // Insert into inbox (receiver is always HOD)
    $stmt = $data->prepare("INSERT INTO `compose_inbox` 
        (sender_email, receiver_email, subject, message) 
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $sender_email, $hod_email, $subject, $message);
    
    if ($stmt->execute()) {
        $success = "Message sent to HOD.";
    } else {
        $error = "Failed to send message.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Compose Message to HOD</title>
    <link rel="stylesheet" href="tcompose.css">
</head>
<body>
<?php include("theader.php"); ?>
<div class="container">
    <h1>Compose Message to HOD</h1>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST">
        <div class="form-group">
            <label>From:</label>
            <input type="email" value="<?php echo $_SESSION['email']; ?>" disabled>
        </div>
        <div class="form-group">
            <label>To (HOD):</label>
            <input type="email" value="<?php echo $hod_email; ?>" disabled> 
        </div>
        <div class="form-group">
            <label>Subject:</label>
            <input type="text" name="subject" required>
        </div>
        <div class="form-group">
            <label>Message:</label>
            <textarea name="message" required></textarea>
        </div>
        <button type="submit">Send</button>
    </form>
</div>
</body>
</html>
