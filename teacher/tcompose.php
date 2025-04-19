<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['faculty'])) {
    header("Location: index1.php");
    exit();
}

$faculty = $_SESSION['faculty'];
$sender_email = $faculty['email'];
$sender_role = $faculty['role'];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Get recipient's role
    $stmt = $data->prepare("SELECT role FROM teacher WHERE email = ?");
    $stmt->bind_param("s", $to);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipient = $result->fetch_assoc();

    if (!$recipient) {
        $error = "Recipient not found.";
    } else {
        $recipient_role = $recipient['role'];

        // Validation based on roles
        if ($sender_role === 'faculty') {
            // Faculty can send messages to HOD or admin
            if ($recipient_role !== 'HOD' && $recipient_role !== 'admin') {
                $error = "You can only send messages to HOD or admin.";
            }
        } elseif ($sender_role === 'HOD') {
            // HOD can send messages to faculty or admin
            if ($recipient_role !== 'faculty' && $recipient_role !== 'admin') {
                $error = "You can only send messages to faculty or admin.";
            }
        } else {
            $error = "Invalid role.";
        }

        if (empty($error)) {
            $stmt = $data->prepare("INSERT INTO messages (sender_email, receiver_email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $sender_email, $to, $subject, $message);
            $stmt->execute();
            $success = "Message sent successfully.";
        }
    }
}
?>

<!-- HTML Form Below -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Compose Message</title>
    <link rel="stylesheet" href="tcompose.css">
</head>
<body>
<?php include("theader.php"); ?>
<div class="container">
    <h1>Compose Message</h1>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success-message'>$success</p>"; ?>
 <form method="POST">
    <div class="form-group">
        <label for="from">From:</label>
        <input type="email" name="from" value="<?php echo htmlspecialchars($sender_email); ?>" disabled>
    </div>
    <div class="form-group">
        <label for="to">
            To (<?php echo ($sender_role === 'HOD') ? 'Faculty/Admin' : 'HOD/Admin'; ?> Email):
        </label>
        <input type="email" name="to" required>
    </div>
    <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" name="subject" required>
    </div>
    <div class="form-group">
        <label for="message">Message:</label>
        <textarea name="message" required></textarea>
    </div>
    <button type="submit">Send</button>
</form>

</div>
</body>
</html>
