<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['faculty'])) {
    die("Access denied.");
}

$faculty_id = $_SESSION['faculty']['faculty_id'];  // ✅ Correct way to get faculty_id

// Get values from form
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$pincode = $_POST['pincode'];
$district = $_POST['district'];
$state = $_POST['state'];

$photo_name = null;

// Handle photo upload if a new photo was submitted
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo_tmp = $_FILES['photo']['tmp_name'];
    $photo_name = basename($_FILES['photo']['name']);
    $target_path = "uploads/" . $photo_name;

    if (!file_exists("uploads")) {
        mkdir("uploads");
    }

    move_uploaded_file($photo_tmp, $target_path);
}

// Prepare SQL
$sql = "UPDATE teacher SET 
            phone_number = ?, 
            address = ?, 
            pincode = ?, 
            district = ?, 
            state = ?";

if ($photo_name) {
    $sql .= ", photo = ?";
}
$sql .= " WHERE faculty_id = ?";

$stmt = $conn->prepare($sql);

if ($photo_name) {
    $stmt->bind_param("ssssssi", $phone_number, $address, $pincode, $district, $state, $photo_name, $faculty_id);
} else {
    $stmt->bind_param("sssssi", $phone_number, $address, $pincode, $district, $state, $faculty_id);
}

if ($stmt->execute()) {

    // ✅ Refresh session data after successful update
    $refresh = $conn->prepare("SELECT * FROM teacher WHERE faculty_id = ?");
    $refresh->bind_param("i", $faculty_id);
    $refresh->execute();
    $result = $refresh->get_result();
    $_SESSION['faculty'] = $result->fetch_assoc();

    header("Location: tprofile.php?updated=true");
    exit();
} else {
    echo "Update failed: " . $stmt->error;
}
?>
