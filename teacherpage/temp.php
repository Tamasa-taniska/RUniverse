<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';

$email = $_SESSION['email'];

// First check if user is HOD
$hod_check = $data->prepare("SELECT designation FROM faculty WHERE email = ?");
$hod_check->bind_param("s", $email);
$hod_check->execute();
$designation_result = $hod_check->get_result()->fetch_assoc();
$is_hod = (strpos($designation_result['designation'] ?? '', '(HOD)') !== false);

// Then fetch messages
$stmt = $data->prepare("SELECT m.*, 
                        CONCAT(u.first_name, ' ', u.last_name) AS name, 
                        f.designation
                        FROM `compose_inbox` m
                        LEFT JOIN faculty f ON m.sender_email = f.email
                        LEFT JOIN users u ON f.faculty_id = u.user_id
                        WHERE (m.receiver_email = ? OR m.receiver_email = 'all_faculty')
                        AND m.sender_email != ?");

$stmt->bind_param("ss", $email, $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inbox</title>
    <link rel="stylesheet" href="t_inbox.css">
</head>
<body>
<?php include("theader.php"); ?>
<div class="inbox-container">
    <h2><?php echo $is_hod ? 'HOD Inbox' : 'Inbox'; ?></h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($msg = $result->fetch_assoc()): ?>
            <div class="message">
                <div class="message-header">
                    <span class="sender">
                        <?php echo htmlspecialchars($msg['name']) . " (" . htmlspecialchars($msg['designation']) . ")"; ?>
                    </span>
                    <span class="timestamp"><?php echo $msg['timestamp']; ?></span>
                </div>
                <div class="subject"><?php echo htmlspecialchars($msg['subject']); ?></div>
                <div class="message-body"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                <?php if ($msg['receiver_email'] === 'all_faculty'): ?>
                    <div class="broadcast-flag">(Broadcast to all faculty)</div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>
</body>
</html>