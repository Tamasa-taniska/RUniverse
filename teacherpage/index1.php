<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <form action="login_check.php" method="post" id="loginForm">
        <h2>Login As 
            <!-- <form action="#"> -->
      <!-- <label for="lang"></label> -->
      <select name="USER" id="lang">
        <option value="admin">ADMIN</option>
        <option value="faculty">FACULTY</option>
        <option value="students">STUDENT</option>
      </select>
        <!-- </form> -->
    </h2>

        <h4>
            <?php 

            //error_reporting(0);
        //     session_start();
        //     session_destroy();
    
        // echo $_SESSION['loginMessage'];
    

            ?>

        </h4>
<h4>
  <?php 
    if (isset($_SESSION['loginMessage'])) {
    echo "<div style='color: red; background: #ffe0e0; padding: 10px; border: 1px solid red; border-radius: 5px; margin-top: 10px;'>".$_SESSION['loginMessage']."</div>";
    unset($_SESSION['loginMessage']);
    }
  ?>

</h4>


        <!-- <form action="login_check.php" method="post" id="loginForm"> -->
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
            <!-- <p id="errorMessage" class="error-message"></p> -->
            <!-- <p><a href="#" id="forgotPassword">Forgot Password?</a></p> -->
            <p><a href="forgot_password.php" id="forgotPassword" name="forgotpassword" >Forgot Password?</a></p>
        </form>
    </div>
    <?php
if (isset($error)) {
    echo "<p style='color: red; background: #ffe0e0; padding: 10px; border: 1px solid red; border-radius: 5px; margin-top: 10px;'>$error</p>";
}
?>

    <!-- <script src="scripts.js"></script> -->
</body>
</html>
