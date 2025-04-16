<?php
// include('../includes/db.php');
include("../../studentpage/dbconnect.php");

$student_id = $_POST['student_id'];
$course_id = $_POST['course_id'];
$semester = $_POST['semester'];
$sgpa = $_POST['sgpa'];
$overall_result = $_POST['overall_result'];
$subject_ids = $_POST['subject_ids']; // ← this must match input name
$marks = $_POST['marks'];             // ← same here

// Optional: Basic validation
if (!is_array($subject_ids) || !is_array($marks)) {
    die("Invalid form data received.");
}

for ($i = 0; $i < count($subject_ids); $i++) {
    $subj_id = $subject_ids[$i];
    $mark = $marks[$i];

    // Insert each subject's marks
    $data->query("INSERT INTO marks (student_id, course_id, semester, subject_id, marks, sgpa, overall_result)
                  VALUES ($student_id, $course_id, $semester, $subj_id, $mark, $sgpa, '$overall_result')");
}

echo "Marks uploaded successfully.";
?>
