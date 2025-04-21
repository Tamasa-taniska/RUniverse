<?php
include("../../studentpage/dbconnect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? '';
    $semester = $_POST['semester'] ?? '';
    
    if ($course_id && $semester) {
        $stmt = $data->prepare("SELECT subject_name FROM subjects WHERE course_id = ? AND semester = ?");
        $stmt->bind_param("ii", $course_id, $semester);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $subjects = [];
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
        echo json_encode($subjects);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>