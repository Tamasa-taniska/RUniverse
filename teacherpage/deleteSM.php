<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
include '../studentpage/dbconnect.php';
$faculty_email = $_SESSION['email']; 
$file = $_POST['file'] ?? '';
$uploadDir = "uploads/";
$filePath = $uploadDir . basename($file);

// Only delete if it exists
if ($file && file_exists($filePath)) {
    unlink($filePath);

    // Delete record from DB
    include("../studentpage/dbconnect.php");
    $stmt = $data->prepare("DELETE FROM study_materials WHERE file_name = ? AND faculty_email = ?");
    $stmt->bind_param("ss", $file, $faculty_email);
    $stmt->execute();

    $_SESSION['message'] = "✅ File deleted successfully!";
    header("Location: StudyMaterials.php");
} else {
    echo "File not found or permission denied.";
}
?>
