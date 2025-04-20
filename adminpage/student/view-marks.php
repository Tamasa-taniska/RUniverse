<?php
session_start();
include("../../studentpage/dbconnect.php");

$email = $_SESSION['email'] ?? null;
if (!$email) {
    header("Location: /ravenshaw/studentpage/login.php");
    exit;
}

$student = $data->query("SELECT s.*, u.first_name, u.middle_name, u.last_name, c.course_name
                         FROM students s
                         JOIN users u ON s.student_id = u.user_id
                         JOIN courses c ON s.course_id = c.course_id
                         WHERE s.email = '$email'")->fetch_assoc();

$student_id = $student['student_id']; 
$course_name = $student['course_name'];
$student_name = $student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name'];

$imgpath = "/ravenshaw/studentpage/uploads/default_photo.jpg";
$photoCheck = $data->query("SELECT photo FROM students WHERE student_id = '$student_id'");
$photoRow = $photoCheck->fetch_assoc();

if ($photoRow['photo'] == 1) {
    $jpgPath = "/ravenshaw/studentpage/uploads/profile" . $student_id . ".jpg";
    $pngPath = "/ravenshaw/studentpage/uploads/profile" . $student_id . ".png";
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $jpgPath)) {
        $imgpath = $jpgPath;
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . $pngPath)) {
        $imgpath = $pngPath;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Marks</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('/ravenshaw/studentpage/header.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        });
</script>

<div id="marks-content">
    <div class="header-container">
        <div class="university-header">
            <img src="../assets/logo.png" alt="University Logo" class="university-logo">
            <div class="header-info">
                <h1>RAVENSHAW UNIVERSITY</h1>
                <h2>CUTTACK</h2>
                <p>Semester Mark Statement For: <?= $course_name ?> CBCS</p>
                <p>SEMESTER-<?= isset($_POST['semester']) ? $_POST['semester'] : 'I' ?> Examination</p>
            </div>
        </div>
        
        <div class="student-details">
            <div class="student-photo-container">
                <img src="<?= htmlspecialchars($imgpath) ?>" alt="Student Photo" class="student-photo">
            </div>
            <div class="student-info">
                <h3>Name: <?= $student_name ?></h3>
                <h4>Roll No: <?= $student['roll_number'] ?></h4>
            </div>
        </div>
    </div>

    <div class="semester-selector no-print">
        <form method="POST" action="">
            <label>Semester:</label>
            <select name="semester" id="semester" onchange="this.form.submit()">
                <option value="">Select Semester</option>
                <?php for ($i = 1; $i <= 6; $i++) { ?>
                    <option value="<?= $i ?>" <?= isset($_POST['semester']) && $_POST['semester'] == $i ? 'selected' : '' ?>>
                        Semester <?= $i ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <?php if (isset($_POST['semester'])) : ?>
    <div class="marks-container">
        <?php
        $semester = $_POST['semester'];
        $marks_sql = "SELECT m.*, sub.subject_name, sub.max_marks, sub.pass_marks
                      FROM marks m
                      JOIN subjects sub ON m.subject_id = sub.subject_id
                      WHERE m.student_id = $student_id AND m.semester = $semester";
        $marks_result = $data->query($marks_sql);
        
        if ($marks_result->num_rows > 0) : ?>
            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Max Marks</th>
                        <th>Pass Marks</th>
                        <th>Marks Scored</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_sgpa = 0;
                    $count = 0;
                    $overall_result = 'Pass';
                    while ($row = $marks_result->fetch_assoc()) :
                        $res = ($row['marks'] >= $row['pass_marks']) ? 'Pass' : 'Fail';
                        $overall_result = $res === 'Fail' ? 'Fail' : $overall_result;
                    ?>
                    <tr>
                        <td><?= $row['subject_name'] ?></td>
                        <td><?= $row['max_marks'] ?></td>
                        <td><?= $row['pass_marks'] ?></td>
                        <td><?= $row['marks'] ?></td>
                        <td><?= $res ?></td>
                    </tr>
                    <?php
                        $total_sgpa += $row['sgpa'];
                        $count++;
                    endwhile;
                    $avg_sgpa = $count ? round($total_sgpa / $count, 2) : 'N/A';
                    ?>
                </tbody>
                <tfoot>
                    <tr class="sgpa-row">
                        <td colspan="3"><strong>Total SGPA</strong></td>
                        <td colspan="2"><strong><?= $avg_sgpa ?> (<?= $overall_result ?>)</strong></td>
                    </tr>
                </tfoot>
            </table>
        <?php else : ?>
            <p class="no-marks">No marks found for this semester.</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<div class="actions no-print">
        <button onclick="downloadPDF()">📥 Download as PDF</button>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const element = document.getElementById('marks-content');
    const options = {
        margin: [0.5, 0.5],
        filename: 'Marksheet-Semester-<?= isset($_POST['semester']) ? htmlspecialchars($_POST['semester']) : 'N/A' ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { 
            scale: 2,
            useCORS: true,
            scrollX: 0,
            scrollY: 0
        },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(options).from(element).save();
}
</script> 
</body>
</html>