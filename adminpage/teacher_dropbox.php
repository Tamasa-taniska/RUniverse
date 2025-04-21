<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    echo "Redirecting to login page...<br>";
    session_destroy();
    header("location: ../studentpage/login.php");
    exit();
}

$email = $_SESSION['email']; // Admin's email

include("../studentpage/dbconnect.php");

$stmt = $data->prepare("
    SELECT sender_email, subject, message, timestamp 
    FROM compose_inbox 
    WHERE receiver_email = ? 
    ORDER BY timestamp DESC
");

// Bind and execute
$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    die("Failed to execute query: " . $stmt->error);
}

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inbox</title>
    <style>
        /* General Styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

/* Container Styling */
.container {
    width: 800px; /* Fixed width */
    margin: 50px auto;
    padding: 20px;
    background: rgb(164, 21, 21);
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Page Header Styling */
h1 {
    text-align: center;
    color: white;
    margin-bottom: 20px;
    font-size: 24px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Message Block Styling */
.message-block {
    padding: 20px;
    margin: 15px 0;
    background-color: #fdfdfd;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.message-block:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
}

/* Text Styling Inside Message Block */
.message-block h3 {
    margin: 0 0 5px;
    color: #555;
    font-size: 18px;
}

.message-block p {
    margin: 5px 0;
    color: #666;
    line-height: 1.6;
}

.message-block span {
    font-size: 12px;
    color: #999;
    display: block;
    text-align: right;
}

/* Fallback Text for No Messages */
.no-messages {
    text-align: center;
    color: #777;
    font-size: 16px;
    margin-top: 20px;
}

    </style>
</head>
<body>
<div id="header-placeholder"></div>
    <script>
        // Load the header content from header.php
        fetch('a_header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>
<div class="container">
    <h1>Admin Inbox</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message-block">
                <h3>From: <?= htmlspecialchars($row['sender_email']); ?></h3>
                <p><strong>Subject:</strong> <?= htmlspecialchars($row['subject']); ?></p>
                <p><?= nl2br(htmlspecialchars($row['message'])); ?></p>
                <span>Received at: <?= htmlspecialchars($row['timestamp']); ?></span>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>
</body>
</html>
