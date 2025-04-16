<?php
// include('../includes/db.php');
include("../../studentpage/dbconnect.php");
$course = $_GET['course'];
$batch = $_GET['batch'];

$result = $data->query("SELECT student_id, roll_number FROM students WHERE department IN 
    (SELECT course_name FROM courses WHERE course_id = $course) 
    AND batch = $batch AND status='Active'");

$options = "<option value=''>Select Student</option>";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='{$row['student_id']}'>{$row['roll_number']}</option>";
}

echo $options;
?>
