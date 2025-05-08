<?php
include("../studentpage/dbconnect.php"); // Your DB connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $host = "localhost";
    // $username = "root"; // use your DB username
    // $password = "";     // use your DB password
    // $dbname = "notice"; // your DB name

    // $data = new mysqli($host, $username, $password, $dbname);

    // if ($data->connect_error) {
    //     die("Database connection failed: " . $data->connect_error);
    // }

    // Fetch data from POST
    $notice = $_POST['notice'] ?? '';
    $user = $_POST['user'] ?? '';

    if (empty($notice)) {
        echo "<script>alert('Notice text is required.');</script>";
    } else {
        $stmt = $data->prepare("INSERT INTO notice (notice_text, user_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $notice, $user);

        if ($stmt->execute()) {
            echo "<script>alert('Notice saved successfully!');</script>";
        } else {
            echo "<script>alert('Failed to save notice.');</script>";
        }

        $stmt->close();
        $data->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notice Form</title>
    <style>
        :root {
            --primary-color:rgb(177, 19, 19);
            --secondary-color:rgb(177, 19, 19);;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: rgb(177, 19, 19);;
            --error-color: #ff3333;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        select:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(75, 181, 67, 0.2);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-error {
            background-color: rgba(255, 51, 51, 0.2);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo img {
            height: 60px;
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
        
        <h1>Post a New Notice</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="user">Notice For:</label>
                <select name="user" id="user" class="select" required>
                    <option value="">Select Audience</option>
                    <option value="students">Students</option>
                    <option value="faculty">Faculty</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="notice">Notice Content:</label>
                <textarea name="notice" id="notice" placeholder="Enter your notice here..." required></textarea>
            </div>
            
            <button type="submit" class="btn">Publish Notice</button>
        </form>
    </div>
</body>
</html>