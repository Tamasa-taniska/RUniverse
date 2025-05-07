<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "students") {
    session_destroy();
    header("location: login.php");
    exit();
}

include 'dbconnect.php';

$email = $_SESSION['email'];

$hod_query = $data->prepare("SELECT email FROM faculty WHERE designation LIKE '%HOD%'");
if (!$hod_query) {
    die("SQL prepare failed: " . $data->error);
}
$hod_query->execute();
$hod_result = $hod_query->get_result();
$hod = $hod_result->fetch_assoc();

if (!$hod) {
    die("HOD not found in the system.");
}

$hod_email = $hod['email'];
$receiver_role = "HOD";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sender_email = $_SESSION['email'];

    $stmt = $data->prepare("INSERT INTO `compose_inbox` 
        (sender_email, receiver_email, subject, message, receiver_role) 
        VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $data->error);
    }

    $stmt->bind_param("sssss", $sender_email, $hod_email, $subject, $message, $receiver_role);

    if ($stmt->execute()) {
        $success = "Message sent to HOD.";
    } else {
        $error = "Failed to send message: " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compose Message to HOD</title>
    <link rel="stylesheet" href="studentStyles.css">
</head>
<body>
    <div id="header-placeholder"></div>

    <script>
        fetch('header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>
<div class="compose-body">
    <div id="compose-email">
        <h1>Compose Message to HOD</h1>
        <?php if ($error): ?>
            <p style="color: red; margin-bottom: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green; margin-bottom: 15px;"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <label>From:</label>
            <input type="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>

            <label>To (HOD):</label>
            <input type="email" value="<?php echo htmlspecialchars($hod_email); ?>" readonly>

            <label>Subject:</label>
            <input type="text" name="subject" required>

            <label>Message:</label>
            <textarea name="message" rows="6" required></textarea>

            <button type="submit">Send</button>
        </form>
    </div>
    </div>
</body>
</html>