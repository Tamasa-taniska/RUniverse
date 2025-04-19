<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['faculty'])) {
    header("Location: index1.php");
    exit();
}

$faculty = $_SESSION['faculty'];
$my_email = $faculty['email'];

$stmt = $data->prepare("SELECT m.*, t.name, t.designation 
                        FROM messages m
                        JOIN teacher t ON m.sender_email = t.email
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
