<?php
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
</html>