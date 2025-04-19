<?php
session_start();
if (!isset($_SESSION['faculty'])) {
    header("Location: index1.php");
    exit();
}

$faculty_email = $_SESSION['faculty']['email'];
$file = $_POST['file'] ?? '';
$uploadDir = "uploads/";
$filePath = $uploadDir . basename($file);

// Only delete if it exists
if ($file && file_exists($filePath)) {
    unlink($filePath);

    // Delete record from DB
    include("db_connect.php");
    $stmt = $conn->prepare("DELETE FROM study_materials WHERE file_name = ? AND faculty_email = ?");
    $stmt->bind_param("ss", $file, $faculty_email);
    $stmt->execute();

    $_SESSION['message'] = "✅ File deleted successfully!";
    header("Location: StudyMaterials.php");
} else {
    echo "File not found or permission denied.";
}
?>
