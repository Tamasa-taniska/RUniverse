<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';
$currentEmail = $_SESSION['email']; 

// Get designation of the logged-in faculty
$designationQuery = "SELECT designation FROM faculty WHERE email = '$currentEmail'";
$designationResult = mysqli_query($data, $designationQuery);

if (!$designationResult || mysqli_num_rows($designationResult) === 0) {
    echo "Designation not found for the current user.";
    exit;
}

$designationRow = mysqli_fetch_assoc($designationResult);
$designation = $designationRow['designation'];

// Decide inbox query based on designation
if (strpos($designation, '(HOD)') !== false) {
    // HOD: Can see messages from admin and other faculty
    $inboxQuery = "SELECT * FROM compose_inbox 
                   WHERE receiver_email IN ('$currentEmail', 'all_faculty')
                   AND sender_email != '$currentEmail'
                   ORDER BY timestamp DESC";
} else {
    // Non-HOD: Can see messages only from admin
    $inboxQuery = "SELECT * FROM compose_inbox 
                   WHERE receiver_email IN ('$currentEmail', 'all_faculty')
                   AND sender_email != '$currentEmail'
                   AND sender_email LIKE '%admin%'
                   ORDER BY timestamp DESC";
}

$inboxResult = mysqli_query($data, $inboxQuery);
?>

<!DOCTYPE html>
<html>
<head>
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
    // Load the header content from theader.php
    fetch('theader.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
            
            // Now look for the logout button INSIDE this then block
            const logoutButton = document.getElementById("logoutButton");
            if (logoutButton) {
                logoutButton.addEventListener("click", function () {
                    window.location.href = "/ravenshaw/studentpage/logout.php";
                });
                console.log("Logout button event listener added.");
            } else {
                console.error("Logout button not found!");
            }
        })
        .catch(error => {
            console.error('Error loading header:', error);
        });
</script>
<h2>Inbox for <?= htmlspecialchars($currentEmail); ?></h2>

<?php if (mysqli_num_rows($inboxResult) > 0): ?>
    <table>
        <tr>
            <th>From</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Time</th>
        </tr>
        <?php while($msg = mysqli_fetch_assoc($inboxResult)): ?>
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
