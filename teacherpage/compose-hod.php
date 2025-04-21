<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
$email = $_SESSION['email'];

include '../studentpage/dbconnect.php';

// Get the admin's email
$admin_query = $data->prepare("
    SELECT a.email 
    FROM admin a 
    JOIN users u ON a.admin_id = u.user_id 
");
$admin_query->execute();
$admin_result = $admin_query->get_result();
$admin = $admin_result->fetch_assoc();

if (!$admin) {
    die("Admin not found in the system.");
}

$admin_email = $admin['email']; // Admin's email
$error = "";
$success = "";

// Send message logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sender_email = $_SESSION['email'];

    // Insert into inbox (receiver is always Admin)
    $stmt = $data->prepare("INSERT INTO `compose-inbox` 
        (sender_email, receiver_email, subject, message) 
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $sender_email, $admin_email, $subject, $message);
    
    if ($stmt->execute()) {
        $success = "Message sent to Admin.";
    } else {
        $error = "Failed to send message.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Compose Message to Admin</title>
    <link rel="stylesheet" href="tcompose.css">
</head>
<body>
<?php include("theader.php"); ?>
<div class="container">
    <h1>Compose Message to Admin</h1>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST">
        <div class="form-group">
            <label>From:</label>
            <input type="email" value="<?php echo $_SESSION['email']; ?>" disabled>
        </div>
        <div class="form-group">
            <label>To (Admin):</label>
            <input type="email" value="<?php echo $admin_email; ?>" disabled> 
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
