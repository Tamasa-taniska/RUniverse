<?php
session_start();
include("db_connect.php"); // use correct DB connection

$notes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];

    $stmt = $conn->prepare("SELECT * FROM study_materials WHERE semester = ? AND subject = ?");
    $stmt->bind_param("ss", $semester, $subject);
    $stmt->execute();
    $result = $stmt->get_result();
    $notes = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Study Materials</title>
    <link rel="stylesheet" href="StudyMaterials.css">
</head>
<body>
    <div id="header-placeholder"></div>
    <script>
        fetch('header.php')
            .then(res => res.text())
            .then(data => document.getElementById('header-placeholder').innerHTML = data);
    </script>

    <div class="container">
        <h1>View Study Materials</h1>
        <form method="POST">
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
                <label for="subject">Subject</label>
                <input type="text" name="subject" required>
            </div>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($notes)): ?>
            <div class="uploaded-materials">
                <h2>Available Study Materials</h2>
                <ul>
                    <?php foreach ($notes as $note): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($note['subject']); ?> (Semester <?php echo $note['semester']; ?>)</strong><br>
                            <a href="uploads/<?php echo htmlspecialchars($note['file_name']); ?>" download>Download PDF</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p style="color: white; background-color: red; padding: 10px;">No materials found for the selected semester and subject.</p>
        <?php endif; ?>
    </div>
</body>
</html>
