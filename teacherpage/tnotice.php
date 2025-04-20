<!DOCTYPE html>
<html>
<head>
    <title>Teacher's Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, textarea, button {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        .btn-submit {
            background-color: #971a1a;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .btn-submit:hover {
            background-color: #971a1a;
        }
    </style>
</head>
<body>
    <div id="header-placeholder"></div>
<script>
    // Load the header content from header.html
    fetch('theader.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        });
</script>
    <div class="container">
        <h1>Upload Message</h1>
        <form action="upload.php" method="POST">
            <label for="semester_id">Semester:</label>
            <input type="number" id="semester_id" name="semester_id" required>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <label for="teacher_name">Your Name:</label>
            <input type="text" id="teacher_name" name="teacher_name" required>
            <button type="submit" class="btn-submit">Upload</button>
        </form>
    </div>
</body>
</html>
