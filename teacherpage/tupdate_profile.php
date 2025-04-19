<?php
session_start();
include '../studentpage/dbconnect.php';

// Ensure user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    die("Access denied.");
}

// Get faculty_id from URL
$faculty_id = $_GET['faculty_id'] ?? null;

if (!$faculty_id) {
    die("Faculty ID is required.");
}

// Fetch current faculty data
$sql = "SELECT * FROM faculty WHERE faculty_id = ?";
$stmt = $data->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();

if (!$faculty) {
    die("Faculty not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from form
    $phone_number = $_POST['phone_number'];
    $address = $_POST['House_No_Building_Name'];
    $pincode = $_POST['pincode'];
    $district = $_POST['City'];
    $state = $_POST['State'];

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
    $sql = "UPDATE faculty SET 
                phone_number = ?, 
                House_No_Building_Name = ?, 
                pincode = ?, 
                City = ?, 
                State = ?";
    
    if ($photo_name) {
        $sql .= ", photo = ?";
    }
    $sql .= " WHERE faculty_id = ?";

    $stmt = $data->prepare($sql);

    if ($photo_name) {
        $stmt->bind_param("ssssssi", $phone_number, $address, $pincode, $district, $state, $photo_name, $faculty_id);
    } else {
        $stmt->bind_param("sssss", $phone_number, $address, $pincode, $district, $state, $faculty_id);
    }

    if ($stmt->execute()) {
        // Refresh session data after successful update
        $refresh = $data->prepare("SELECT * FROM faculty WHERE faculty_id = ?");
        $refresh->bind_param("i", $faculty_id);
        $refresh->execute();
        $result = $refresh->get_result();
        $_SESSION['faculty'] = $result->fetch_assoc();

        header("Location: tprofile.php?updated=true");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
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