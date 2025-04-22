<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}

include '../studentpage/dbconnect.php';

$currentEmail = $_SESSION['email'];

// Retrieve messages for the faculty member, including those sent to 'all_faculties'
$inboxQuery = "SELECT * FROM compose_inbox WHERE receiver_email = ? OR receiver_email = 'all_faculties' ORDER BY timestamp DESC";
$inboxStmt = $data->prepare($inboxQuery);
$inboxStmt->bind_param("s", $currentEmail);
$inboxStmt->execute();
$inboxResult = $inboxStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Inbox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #a72222cc;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-message {
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('theader.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        })
        .catch(error => console.error('Error loading header:', error));
</script>

<h2>Inbox for <?= htmlspecialchars($currentEmail); ?></h2>

<?php if ($inboxResult->num_rows > 0): ?>
    <table>
        <tr>
            <th>From</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Time</th>
        </tr>
        <?php while ($msg = $inboxResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($msg['sender_email']); ?></td>
                <td><?= htmlspecialchars($msg['subject']); ?></td>
                <td><?= nl2br(htmlspecialchars($msg['message'])); ?></td>
                <td><?= htmlspecialchars($msg['timestamp']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p class="no-message">No messages to show.</p>
<?php endif; ?>
</body>
</html>
