<!-- <?php
include 'dbconnect.php';

$role = $_POST['role'] ?? '';
$email = $_POST['email'] ?? '';
$step = 'verify'; // default for user whenever he hits 'forget password' in login page.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_email'])) {
        // Step 1: Verify email
        if ($role === 'students' || $role === 'faculty' || $role === 'admin') {
            $stmt = $data->prepare("SELECT * FROM $role WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $step = 'change'; // move to password change step
            } else {
                $_SESSION['checkemail']="Email not found in $role table";
            }
        }
    }

    if (isset($_POST['change_password'])) {
        // Step 2: Update password
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if ($new_password !== $confirm_password) {
            $_SESSION['message']="Passwords do not match.";
            $step = 'change'; // stay on same step
        }
        elseif ($role && $email && $new_password) {
            $stmt = $data->prepare("UPDATE $role SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $new_password, $email);
            if ($stmt->execute()) {
                echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Failed to update password.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h3 {
    margin-left: 40px;
    display: flex;
    align-items: center;
    gap: 10px; /* Adjust spacing */
}

h3 select {
    font-size: 1rem;
    padding: 5px;
}
    </style>
</head>
<body>

<div class="login-container">
    <h2>Forgot Password</h2>

    <?php if ($step === 'verify'): ?>
        <form method="POST">
            <h3><label>Select Role:</label>
            <select name="role" required>
                <option value="">--Select--</option>
                <option value="admin" <?= ($role === 'admin') ? 'selected' : '' ?>>ADMIN</option>
                <option value="faculty" <?= ($role === 'faculty') ? 'selected' : '' ?>>FACULTY</option>
                <option value="students" <?= ($role === 'students') ? 'selected' : '' ?>>STUDENT</option>
            </select><br>
        </h3>
            <p><b><?php 
    if (isset($_SESSION['checkemail'])) {
        echo $_SESSION['checkemail'];
        unset($_SESSION['checkemail']); // Remove message after displaying
    }
    ?></b></p>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br>

            <button type="submit" name="verify_email">Verify Email</button>
        </form>
    <?php elseif ($step === 'change'): ?>
        <form method="POST">
            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

            <p><b><?php 
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Remove message after displaying
    }
    ?></b></p>

            <label>New Password:</label>
            <input type="password" name="new_password" required><br>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required><br>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html> -->

<?php
session_start();
include 'dbconnect.php'; // assuming your DB connection is here

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Handle session destroy if navigating away
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Send OTP
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'runiverse505@gmail.com';
        $mail->Password   = 'lxyp jmpl pzdg juhe';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('runiverse505@gmail.com', 'Do Not Reply');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP for Password Reset';
        $mail->Body    = "Your OTP is <b>$otp</b>. Valid for 2 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$msg = '';
$step = $_SESSION['step'] ?? 1;

// Step 1: Email & Role
if (isset($_POST['send_otp']) && $step === 1) {
    $email = $_POST['email'];
    $role = $_POST['role'];

    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;

    // Map role to table
    switch ($role) {
        case 'students':
            $table = 'students';
            break;
        case 'faculty':
            $table = 'faculty';
            break;
        case 'admin':
            $table = 'admin';
            break;
        default:
            die("Invalid role.");
    }

    // Check if email exists
    $stmt = $data->prepare("SELECT email FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 120; // 2 min
        $_SESSION['step'] = 2;

        if (sendOTP($email, $otp)) {
            $msg = "OTP sent successfully!";
        } else {
            $msg = "Failed to send OTP!";
        }
    } else {
        $msg = "Email not found!";
    }
}

// Step 2: Verify OTP
elseif (isset($_POST['otp']) && $step === 2) {
    if ($_POST['otp'] == $_SESSION['otp'] && time() <= $_SESSION['otp_expiry']) {
        $_SESSION['step'] = 3;
    } else {
        $msg = "Invalid or expired OTP!";
    }
}

// Step 3: Reset Password
elseif (isset($_POST['newpass']) && isset($_POST['confirmpass']) && $step === 3) {
    if ($_POST['newpass'] === $_POST['confirmpass']) {
        $email = $_SESSION['email'];
        $role = $_SESSION['role'];
        $password = $_POST['newpass'];

        // Map again
        switch ($role) {
            case 'students': $table = 'students'; break;
            case 'faculty':  $table = 'faculty';  break;
            case 'admin':    $table = 'admin';    break;
            default: die("Invalid role.");
        }

        $query = "UPDATE $table SET password = ? WHERE email = ?";
        $stmt = $data->prepare($query);
        $stmt->bind_param("ss", $password, $email);

        if ($stmt->execute()) {
            session_destroy();
            echo "<script>alert('Password updated successfully!');</script>";
            echo "<script>window.location.href='login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to update password.');</script>";
            echo "<script>window.location.href='login.php';</script>";
            exit();
        }
    } else {
        $msg = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Password</title>
    <script>
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            let interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                display.textContent =
                    (minutes < 10 ? "0" : "") + minutes + ":" +
                    (seconds < 10 ? "0" : "") + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    const expired = document.getElementById("otp-expired");
                    if (expired) expired.style.display = "block";
                }
            }, 1000);
        }

        window.onload = function () {
            const timerDisplay = document.getElementById('timer');
            if (timerDisplay) {
                startTimer(120, timerDisplay);
            }

            window.addEventListener("beforeunload", function () {
                navigator.sendBeacon("update_password.php?reset=true");
            });
        };
    </script>
</head>
<body>
    <h2>Update Password</h2>
    <?php if ($msg): ?>
        <p style="color:red;"><?= $msg ?></p>
    <?php endif; ?>

    <?php if ($step === 1): ?>
        <form method="post">
            <label>Email:</label><br>
            <input type="email" name="email" required><br>
            <label>Role:</label><br>
            <select name="role" required>
                <option value="students">Student</option>
                <option value="faculty">Faculty</option>
                <option value="admin">Admin</option>
            </select><br><br>
            <button type="submit" name="send_otp">Send OTP</button>
        </form>

    <?php elseif ($step === 2): ?>
        <form method="post">
            <label>Enter OTP:</label><br>
            <input type="text" name="otp" required><br>
            <p>OTP is valid for: <span id="timer">02:00</span></p>
            <div id="otp-expired" style="color:red; display:none;">OTP expired. Please refresh and try again.</div><br>
            <button type="submit">Verify OTP</button>
        </form>

    <?php elseif ($step === 3): ?>
        <form method="post">
            <label>New Password:</label><br>
            <input type="password" name="newpass" required><br>
            <label>Confirm Password:</label><br>
            <input type="password" name="confirmpass" required><br><br>
            <button type="submit">Update Password</button>
        </form>
    <?php endif; ?>

    <br><a href="login.php">← Back to Login</a>
</body>
</html>
