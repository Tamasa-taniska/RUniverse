<?php
session_start();
include '../studentpage/dbconnect.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== "faculty") {
    die("Access denied.");
}

$faculty_id = $_GET['faculty_id'] ?? null;
if (!$faculty_id) {
    die("Faculty ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone_number'];
    $address = $_POST['House_No_Building_Name'];
    $pincode = $_POST['pincode'];
    $district = $_POST['City'];
    $state = $_POST['State'];

    $photo_uploaded = false;

    // === PHOTO UPLOAD ===
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $newFileName = "profile" . $faculty_id . ".jpg";
        $uploadPath = "uploads/" . $newFileName;

        if (!file_exists("uploads")) {
            mkdir("uploads", 0777, true);
        }

        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
        $photo_uploaded = true;
    }

    // === UPDATE FACULTY TABLE ===
    if ($photo_uploaded) {
        $sqlFaculty = "UPDATE faculty SET phone_number = ?, photo = 1 WHERE faculty_id = ?";
        $stmtFaculty = $data->prepare($sqlFaculty);
        $stmtFaculty->bind_param("si", $phone_number, $faculty_id);
    } else {
        $sqlFaculty = "UPDATE faculty SET phone_number = ? WHERE faculty_id = ?";
        $stmtFaculty = $data->prepare($sqlFaculty);
        $stmtFaculty->bind_param("si", $phone_number, $faculty_id);
    }

    // === UPDATE USERS TABLE ===
    $sqlUser = "UPDATE users SET House_No_Building_Name = ?, pincode = ?, City = ?, State = ? WHERE user_id = ?";
    $stmtUser = $data->prepare($sqlUser);
    $stmtUser->bind_param("ssssi", $address, $pincode, $district, $state, $faculty_id);

    // === EXECUTE BOTH UPDATES ===
    $success = $stmtFaculty->execute() && $stmtUser->execute();

    if ($success) {
        header("Location: tprofile.php?updated=true");
        exit();
    } else {
        echo "Update failed: " . $stmtFaculty->error . " / " . $stmtUser->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Faculty Profile</title>
</head>
<body>
    <h3>Edit Profile</h3>
    <form method="POST" enctype="multipart/form-data">
        <label>Mobile Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($faculty['phone_number']); ?>" required>
        
        <label>Address:</label>
        <input type="text" name="House_No_Building_Name" value="<?php echo htmlspecialchars($faculty['House_No_Building_Name']); ?>" required>
        
        <label>Pincode:</label>
        <input type="text" name="pincode" value="<?php echo htmlspecialchars($faculty['pincode']); ?>" required>
        
        <label>District:</label>
        <input type="text" name="City" value="<?php echo htmlspecialchars ($faculty['City']); ?>" required>
        
        <label>State:</label>
        <input type="text" name="State" value="<?php echo htmlspecialchars($faculty['State']); ?>" required>
        
        <label>Upload New Photo:</label>
        <input type="file" name="photo" accept="image/*">
        
        <button type="submit">Save Changes</button>
    </form>
    <a href="tprofile.php">Cancel</a>
</body>
</html>