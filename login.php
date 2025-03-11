<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <h4>
            <?php 

            error_reporting(0);
            session_start();
            session_destroy();
    
        echo $_SESSION['loginMessage'];
    

            ?>

        </h4>
        <form action="login_check.php" method="post" id="loginForm">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
            <!-- <p id="errorMessage" class="error-message"></p> -->
            <p><a href="forgot_password.php" id="forgotPassword" name="forgotpassword" >Forgot Password?</a></p>
        </form>
    </div>
    <!-- <script src="scripts.js"></script> -->
</body>
</html>