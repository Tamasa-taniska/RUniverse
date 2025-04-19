<?php
session_start();
include("db_connect.php");

// Allow only logged-in faculty
if (!isset($_SESSION['faculty'])) {
    header("Location: index1.php");
    exit();
}

$faculty_email = $_SESSION['faculty']['email'];
$message = "";

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];
    $file = $_FILES['file'];

    if ($file['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        $filename = time() . '_' . basename($file['name']);
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $stmt = $conn->prepare("INSERT INTO study_materials (faculty_email, semester, subject, file_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $faculty_email, $semester, $subject, $filename);
            $stmt->execute();
            $message = "✅ Study material uploaded successfully!";
        } else {
            $message = "❌ Failed to move uploaded file.";
        }
    } else {
        $message = "❌ File upload error.";
    }
}

// Fetch uploaded files for current faculty
$stmt = $conn->prepare("SELECT * FROM study_materials WHERE faculty_email = ? ORDER BY upload_time DESC");
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$uploads = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Study Material Upload</title>
    <link rel="stylesheet" href="StudyMaterials.css">
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('theader.php')
        .then(res => res.text())
        .then(data => document.getElementById('header-placeholder').innerHTML = data);
</script>

<div class="container">
    <h1>Study Materials</h1>
    <?php if ($message): ?>
        <div style="background:#fff;color:black;padding:10px;border-radius:5px;margin-bottom:15px;"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="semester">Semester</label>
            <select name="semester" required>
                <option value="">Select Semester</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="subject">Subject Name</label>
            <input type="text" name="subject" required>
        </div>
        <div class="form-group">
            <label for="file">Upload PDF</label>
            <input type="file" name="file" accept=".pdf" required>
        </div>
        <button type="submit">Upload</button>
    </form>

    <div class="uploaded-materials">
        <h2>Your Uploaded Materials</h2>
        <ul>
            <?php while ($row = $uploads->fetch_assoc()): ?>
               <li>
                  <?php echo htmlspecialchars($row['subject']); ?> (Semester <?php echo $row['semester']; ?>) -
                  <a href="uploads/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank">View PDF</a>
                  <form action="deleteSM.php" method="POST" style="display:inline;">
                     <input type="hidden" name="file" value="<?php echo htmlspecialchars($row['file_name']); ?>">
                     <button type="submit" onclick="return confirm('Are you sure you want to delete this file?');
                     "style="background-color:red; color:white; border:none; padding:5px 10px; border-radius:4px; margin-left:10px; cursor:pointer;">
                        Delete
                    </button>
                 </form>

               </li>
           <?php endwhile; ?>
        </ul>

    </div>
</div>
</body>
</html>
