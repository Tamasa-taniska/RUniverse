<?php
// Processing the uploaded file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "MySQL@2025", "marksheet_db");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if 'studentId' and 'marksheet' keys exist before accessing them
    if (isset($_POST['studentId']) && isset($_FILES['marksheet'])) {
        $studentId = $_POST['studentId'];
        $targetDir = "uploads/";
        $fileName = basename($_FILES["marksheet"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["marksheet"]["tmp_name"], $targetFilePath)) {
            // Update the database
            $sql = "UPDATE students SET marksheet_path = '$targetFilePath' WHERE id = $studentId";
            if ($conn->query($sql)) {
                $message = "Marksheet uploaded successfully.";
            } else {
                $message = "Database error: " . $conn->error;
            }
        } else {
            $message = "File upload failed.";
        }
    } else {
        $message = "All fields are required!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher's Upload Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        button {
            background-color:rgb(161, 18, 18);
            color: #ffffff;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color:rgb(161, 18, 18);
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dde0e2;
            border-radius: 5px;
            color: #333333;
            text-align: center;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>

<script>
    // Load the header content from header.html
    fetch('theader.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        });
</script>
    <div class="container">
        <h1>Upload Marksheet</h1>
        <?php if (isset($message)): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="studentId">Student ID:</label>
            <input type="text" id="studentId" name="studentId" required>

            <label for="marksheet">Upload Marksheet (PDF):</label>
            <input type="file" id="marksheet" name="marksheet" accept=".pdf" required>

            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
