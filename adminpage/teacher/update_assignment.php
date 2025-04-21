<?php
include("../../studentpage/dbconnect.php");

header('Content-Type: application/json');

$conn= json_decode(file_get_contents('php://input'), true);

$course_id = $conn['course_id'];
$semester = $conn['sem'];
$subject = $conn['sub'];
$fid = $conn['fid'];

try {
    // Check if entry exists
    $checkStmt = $data->prepare("SELECT * FROM course_allotment 
                                WHERE course_id = ? AND sem = ? AND sub = ?");
    $checkStmt->bind_param("iis", $course_id, $semester, $subject);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $data->prepare("UPDATE course_allotment SET faculty_id = ? 
                               WHERE course_id = ? AND sem = ? AND sub = ?");
        $stmt->bind_param("iisi", $fid, $course_id, $semester, $subject);
    } else {
        $stmt = $data->prepare("INSERT INTO course_allotment (course_id, sem, sub, faculty_id) 
                               VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $course_id, $semester, $subject, $fid);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $data->error]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>