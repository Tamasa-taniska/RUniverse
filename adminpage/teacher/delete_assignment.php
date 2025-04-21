<?php
include("../../studentpage/dbconnect.php");

header('Content-Type: application/json');

$conn = json_decode(file_get_contents('php://input'), true);

$course_id = $conn['course_id'];
$semester = $conn['sem'];
$subject = $conn['sub'];

try {
    $stmt = $data->prepare("DELETE FROM course_allotment 
                           WHERE course_id = ? AND sem = ? AND sub = ?");
    $stmt->bind_param("iis", $course_id, $semester, $subject);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $data->error]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>