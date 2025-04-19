<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';

$user_email = $_SESSION['email'];

$stmt = $data->prepare("SELECT m.*, t.name, t.designation 
                        FROM compose-inbox m
                        JOIN faculty t ON m.sender_email = t.email
                        WHERE m.receiver_email = ?
                        ORDER BY m.timestamp DESC");
$stmt->bind_param("s", $my_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inbox</title>
    <link rel="stylesheet" href="t_inbox.css"> <!-- ✅ External CSS linked here -->
</head>
<body>
<?php include("theader.php"); ?>
<div class="inbox-container">
    <h2>Inbox</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($msg = $result->fetch_assoc()): ?>
            <div class="message">
                <div class="sender">
                    From: <?php echo htmlspecialchars($msg['name']) . " (" . htmlspecialchars($msg['designation']) . ")"; ?>
                </div>
                <div class="timestamp"><?php echo $msg['timestamp']; ?></div>
                <div><strong>Subject:</strong> <?php echo htmlspecialchars($msg['subject']); ?></div>
                <div><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>
</body>
</html>
