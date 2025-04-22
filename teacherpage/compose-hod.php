<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}

$email = $_SESSION['email']; // Logged-in faculty member's email
include '../studentpage/dbconnect.php';

// Fetch admin email
$admin_query = $data->prepare("SELECT a.email FROM admin a JOIN users u ON a.admin_id = u.user_id");
$admin_query->execute();
$admin_result = $admin_query->get_result();
$admin = $admin_result->fetch_assoc();
if (!$admin) {
    die("Admin not found in the system.");
}
$admin_email = $admin['email']; // Admin's email

// Fetch individual faculty emails (excluding sender and HODs)
$faculty_query = $data->prepare("SELECT email FROM faculty WHERE email != ? AND designation NOT LIKE '%HOD%'");
$faculty_query->bind_param("s", $email);
$faculty_query->execute();
$faculty_result = $faculty_query->get_result();

// Initialize message handling
$error = "";
$success = "";

// Send message logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receiver_email = $_POST['receiver_email']; // Selected recipient
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sender_email = $_SESSION['email'];
    $receiver_role = "Faculty"; // All recipients are faculty

    if ($receiver_email === "all_faculties") {
        // Set receiver_email as 'all_faculties' to indicate message for all faculty
        $stmt = $data->prepare("INSERT INTO compose_inbox (sender_email, receiver_email, subject, message, receiver_role) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $sender_email, $receiver_email, $subject, $message, $receiver_role);
        if ($stmt->execute()) {
            $success = "Message sent to all faculties excluding HODs.";
        } else {
            $error = "Failed to send message.";
        }
    } else {
        // Send to a single recipient (Admin or individual faculty)
        $stmt = $data->prepare("INSERT INTO compose_inbox (sender_email, receiver_email, subject, message, receiver_role) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $sender_email, $receiver_email, $subject, $message, $receiver_role);
        if ($stmt->execute()) {
            $success = "Message sent successfully.";
        } else {
            $error = "Failed to send message.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Compose Message</title>
    <link rel="stylesheet" href="tcompose.css">
</head>
<body>
<?php include("theader.php"); ?>
<div class="container">
    <h1>Compose Message</h1>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST">
        <div class="form-group">
            <label>From:</label>
            <input type="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>
        </div>
        <div class="form-group">
            <label>To:</label>
            <select name="receiver_email" required>
                <option value="<?php echo htmlspecialchars($admin_email); ?>">Admin</option>
                <option value="all_faculties">All Faculties (Excluding HODs)</option>
                <?php while ($faculty = $faculty_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($faculty['email']); ?>">
                        <?php echo htmlspecialchars($faculty['email']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
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
