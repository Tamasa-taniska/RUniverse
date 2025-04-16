<?php
session_start();
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'dbconnect.php'; // include your DB connection

$step = isset($_SESSION['step']) ? $_SESSION['step'] : 1; // set $step= session value if it exits else assign $step=1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Step 1: Validate role and email
    if (isset($_POST['email']) && isset($_POST['role']) && $step === 1) {
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Map role to table
        switch ($role) {
            case 'students':
                $table = 'students';
                break;
            case 'faculty':
                $table = 'faculty';
                break;
            case 'admin':
                $table = 'admin'; // change if your admin table has a different name
                break;
            default:
                die("Invalid role selected.");
        }

        $query = "SELECT * FROM $table WHERE email = ?";
        $stmt = $data->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['step'] = 2;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'runiverse505@gmail.com'; // your email
                $mail->Password = 'lxyp jmpl pzdg juhe';    // your Gmail app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('runiverse505@gmail.com', 'RUniverse Support');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Secure Password Reset';
                $mail->Body = "
                <p>Hello,</p>
                <p>Your one-time password (OTP) to reset your account is:</p>
                <h2>$otp</h2>
                <p>This OTP is valid for 10 minutes.</p>
                <p>If you didn't request this, please ignore this email.</p>
                <br><p>— RUniverse Support</p>
                ";
                $mail->AltBody = "Your OTP for password reset is $otp. Do not share it with anyone.";

                $mail->send();
                $msg = "OTP sent to your email.";
            } catch (Exception $e) {
                $msg = "Mailer Error: " . $mail->ErrorInfo;
                $_SESSION['step'] = 1;
            }
        } else {
            $msg = "Email not found for the selected role.";
        }
    }

    // Step 2: Verify OTP
    elseif (isset($_POST['otp']) && $step === 2) {
        if ($_POST['otp'] == $_SESSION['otp']) {
            $_SESSION['step'] = 3;
        } else {
            $msg = "Invalid OTP!";
        }
    }

    // Step 3: Reset Password
    elseif (isset($_POST['newpass']) && isset($_POST['confirmpass']) && $step === 3) {
        if ($_POST['newpass'] === $_POST['confirmpass']) {
            $email = $_SESSION['email'];
            $role = $_SESSION['role'];
            $password = $_POST['newpass'];

            // Map role to table again
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

            $query = "UPDATE $table SET password = ? WHERE email = ?";
            $stmt = $data->prepare($query);
            $stmt->bind_param("ss", $password, $email);
            if ($stmt->execute()) {
                //$msg = "Password updated successfully!";
                session_destroy();
                // header("location: login.php");
                echo "<script>alert('Password updated successfully!');</script>";
                echo "<script>window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Failed to update password.');</script>";
                echo "<script>window.location.href='login.php';</script>";
                //$msg = "Failed to update password.";
            }
        } else {
            $msg = "Passwords do not match!";
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
    padding: 2px;
}
    </style>
</head>
<body>
    <div class="login-container"><h2>Forgot Password 
        <a href="login.php" style="font-size: 15px;">HOME</a>
    </h2>
    <p style="color:red;"><?php echo isset($msg) ? $msg : ''; ?></p>

    <?php if (!isset($_SESSION['step']) || $_SESSION['step'] === 1): ?>
        <form method="post">
            <h3><label>Select Role:</label>
            <select name="role" required>
                <option value="">--Select Role--</option>
                <option value="students">Student</option>
                <option value="faculty">Teacher</option>
                <option value="admin">Admin</option>
            </select><br><br></h3>
            
            <label>Enter Email:</label>
            <input type="email" name="email" required><br><br>
            <button type="submit">Send OTP</button> <br><br>
        </form>

    <?php elseif ($_SESSION['step'] === 2): ?>
        <form method="post">
            <label>Enter OTP sent to your email:</label>
            <input type="text" name="otp" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>

    <?php elseif ($_SESSION['step'] === 3): ?>
        <form method="post">
            <label>New Password:</label>
            <input type="password" name="newpass" required><br><br>
            <label>Confirm Password:</label>
            <input type="password" name="confirmpass" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?></div>
    <form action="" method="post">
        
    </form>
    
</body>
</html>
