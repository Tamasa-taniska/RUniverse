<?php
session_start();
// include('../includes/db.php');
include("../../studentpage/dbconnect.php");

// $roll_number = $_SESSION['roll_number'] ?? null;

// if (!$roll_number) {
//     header("Location: ../login.php");
//     exit;
// }

$email = $_SESSION['email'];
if (!$email) {
    header("Location: /ravenshaw/studentpage/login.php");
    exit;
}

// Get student info
$student = $data->query("SELECT s.*, u.first_name, u.middle_name, u.last_name, c.course_name
                         FROM students s
                         JOIN users u ON s.student_id = u.user_id
                         JOIN courses c ON s.course_id = c.course_id
                         WHERE s.email = '$email'")->fetch_assoc();

$student_id = $student['student_id']; 
$course_id = $student['course_id']; 
$course_name = $student['course_name'];
$student_name = $student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name'];

$imgpath = "/ravenshaw/studentpage/uploads/default_photo.jpg"; // Default image path
$photoCheck = $data->query("SELECT photo FROM students WHERE student_id = '$student_id'");
$photoRow = $photoCheck->fetch_assoc();

if ($photoRow['photo'] == 1) {
    $jpgPath = "/ravenshaw/studentpage/uploads/profile" . $student_id . ".jpg";
    $pngPath = "/ravenshaw/studentpage/uploads/profile" . $student_id . ".png";

    $jpgFullPath = $_SERVER['DOCUMENT_ROOT'] . $jpgPath;
    $pngFullPath = $_SERVER['DOCUMENT_ROOT'] . $pngPath;
    
    if (file_exists($jpgFullPath)) {
        $imgpath = $jpgPath;
    } elseif (file_exists($pngFullPath)) {
        $imgpath = $pngPath;
    }}

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Marks</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div id="marks-content">
<div class="header">
    <img class="left-img" src="../assets/logo.png" alt="College Logo">
    <div class="header-text">
    RAVENSHAW UNIVERSITY<br>
    CUTTACK<br>
    Semester Mark Statement For : <?= $course_name ?> CBCS SEMESTER-<?= isset($_POST['semester']) ? $_POST['semester'] : 'I' ?> Examination
</div>
<div class="student-image">
    <!-- <img src="../uploads/<?= $student['photo'] ?>" alt="Student Photo" class="student-photo"> -->
    <img src="<?php echo htmlspecialchars($imgpath); ?>" alt="Student Image" class="student-photo"></div>
    
</div>

<h3>Name: <?= $student_name ?></h3>
<h4>Roll No: <?= $student['roll_number'] ?></h4>

<form method="POST" action="">
    <label>Select Semester:</label>
    <select name="semester" id="semester" onchange="this.form.submit()">
        <option value="">Select Semester</option>
        <?php for ($i = 1; $i <= 6; $i++) { ?>
            <option value="<?= $i ?>" <?= isset($_POST['semester']) && $_POST['semester'] == $i ? 'selected' : '' ?>>Semester <?= $i ?></option>
        <?php } ?>
    </select>
</form>

<?php
// Display marks for the selected semester
if (isset($_POST['semester'])) {
    $semester = $_POST['semester'];
    
    echo "<h3>Semester $semester</h3>";

    $marks_sql = "SELECT m.*, sub.subject_name, sub.max_marks, sub.pass_marks
                  FROM marks m
                  JOIN subjects sub ON m.subject_id = sub.subject_id
                  WHERE m.student_id = $student_id AND m.semester = $semester";
    $marks_result = $data->query($marks_sql);

    if ($marks_result->num_rows > 0) {
        echo "<table class='marks-table'>
                <tr>
                    <th>Subject</th>
                    <th>Max Marks</th>
                    <th>Pass Marks</th>
                    <th>Marks Scored</th>
                    <th>Result</th>
                </tr>";

        $total_sgpa = 0;
        $count = 0;
        $overall_result = 'Pass';

        while ($row = $marks_result->fetch_assoc()) {
            $res = ($row['marks'] >= $row['pass_marks']) ? 'Pass' : 'Fail';
            if ($res === 'Fail') {
                $overall_result = 'Fail';
            }

            echo "<tr>
                    <td>{$row['subject_name']}</td>
                    <td>{$row['max_marks']}</td>
                    <td>{$row['pass_marks']}</td>
                    <td>{$row['marks']}</td>
                    <td>$res</td>
                </tr>";

            $total_sgpa += $row['sgpa'];
            $count++;
        }

        $avg_sgpa = $count ? round($total_sgpa / $count, 2) : 'N/A';

        echo "<tr>
                <td colspan='3'><strong>Total SGPA</strong></td>
                <td colspan='2'><strong>$avg_sgpa ($overall_result)</strong></td>
              </tr>";
        echo "</table>";
    } else {
        echo "<p>No marks found for this semester.</p>";
    }
}
?>
<button onclick="downloadPDF()">📥 Download as PDF</button>
</div>



<script src="download-marks.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</body>
</html>
