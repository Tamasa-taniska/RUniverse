<?php
// include('../includes/db.php');
include("../../studentpage/dbconnect.php");

$course_id = $_GET['course'];
$semester = $_GET['semester'];

$result = $data->query("SELECT * FROM subjects WHERE course_id=$course_id AND semester=$semester");

echo "<table class='marks-table'><tr>
<th>Paper Code</th>
<th>Subject</th>
<th>Max Marks</th>
<th>Pass Marks</th>
<th>Marks Scored</th>
<th>Result</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr class='subject-row' 
        data-max='{$row['max_marks']}' 
        data-pass='{$row['pass_marks']}'>
        <td><input type='hidden' name='subject_ids[]' value='{$row['subject_id']}'>{$row['subject_id']}</td>
        <td>{$row['subject_name']}</td>
        <td>{$row['max_marks']}</td>
        <td>{$row['pass_marks']}</td>
        <td><input type='number' class='marks' name='marks[]' required></td>
        <td class='result'>-</td>
        <input type='hidden' name='subject_sgpa[]' class='sgpa'>
    </tr>";
}

echo "</table>";

