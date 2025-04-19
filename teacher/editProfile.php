<?php
session_start();
include("dbconnect.php"); // Your DB connection file

$email = $_SESSION['email'];

// Get student info using the session email
$studentQuery = mysqli_query($data, "SELECT * FROM students WHERE email='$email'");
$studentData = mysqli_fetch_assoc($studentQuery);
$student_id = $studentData['student_id'];

// Get user info; here student_id equals user_id in the users table
$userQuery = mysqli_query($data, "SELECT * FROM users WHERE user_id='$student_id'");
$userData = mysqli_fetch_assoc($userQuery);
$username = $userData["first_name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Reset profile photo to default
    if (isset($_POST['reset_photo'])) {
        mysqli_query($data, "UPDATE students SET photo=0 WHERE student_id='$student_id'");
        header("Location: profile.php");
        exit();
    }

    // Update phone
    if (!empty($_POST['phone'])) {
        $phone = $_POST['phone'];
        mysqli_query($data, "UPDATE students SET phone_number='$phone' WHERE student_id='$student_id'");
    }

    // Update address fields
    if (!empty($_POST['city']) || !empty($_POST['state']) || !empty($_POST['pincode']) || !empty($_POST['house'])) {
        $city = $_POST['city'] !== '' ? $_POST['city'] : $userData['City'];
        $state = $_POST['state'] !== '' ? $_POST['state'] : $userData['State'];
        $pincode = $_POST['pincode'] !== '' ? $_POST['pincode'] : $userData['pincode'];
        $house = $_POST['house'] !== '' ? $_POST['house'] : $userData['House_No_Building_Name'];

        mysqli_query($data, "UPDATE users SET City='$city', State='$state', Pincode='$pincode', House_No_Building_Name='$house' WHERE user_id='$student_id'");
    }

    // Upload CV
    if (!empty($_FILES['profile_cv']['name'])) {
        $cv_name = basename($_FILES["profile_cv"]["name"]);
        $cv_tmp = $_FILES["profile_cv"]["tmp_name"];
        move_uploaded_file($cv_tmp, "uploads/" . $cv_name);
        mysqli_query($data, "UPDATE students SET profile_cv='$cv_name' WHERE student_id='$student_id'");
    }

    // Upload profile photo
    if (!empty($_FILES['profile_photo']['name'])) {
        $file = $_FILES['profile_photo'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileError = $file['error'];
        $fileSize = $file['size'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg'];

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5 * 1024 * 1024) { // less than 5MB
                    $newName = "profile" . $student_id . "." . $fileExt;
                    $destination = "uploads/" . $newName;
                    move_uploaded_file($fileTmp, $destination);
                    mysqli_query($data, "UPDATE students SET photo=1 WHERE student_id='$student_id'");
                } else {
                    echo "<script>alert('Image too large. Max 5MB');</script>";
                }
            } else {
                echo "<script>alert('Upload error');</script>";
            }
        } else {
            echo "<script>alert('Only JPG format allowed');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    h2 {
        color:  #a71d2a;
        margin-bottom: 20px;
    }

    a {
        text-decoration: none;
        color:  #a71d2a;
        margin-right: 20px;
    }

    form {
        background: #ffffff;
        padding: 30px;
        max-width: 600px;
        margin: 0 auto;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
    }

    input[type="file"] {
        padding: 8px;
        background: #f8f9fa;
    }

    .preview-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 2px solid #ddd;
    }

    button,
    input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        font-size: 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    button:hover,
    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    button[name="reset_photo"] {
        background-color: #dc3545;
    }

    button[name="reset_photo"]:hover {
        background-color: #a71d2a;
    }

    @media (max-width: 600px) {
        form {
            padding: 20px;
        }

        .preview-img {
            width: 120px;
            height: 120px;
        }
    }
</style>

    <script>
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('preview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>

<h2><a href="profile.php">HOME</a>
    Edit Profile</h2>

<form method="POST" enctype="multipart/form-data">

    <!-- Profile Photo Upload -->
    <label>Profile Photo (jpg format):</label>
    <input type="file" name="profile_photo" accept=".jpg,.png" onchange="previewPhoto(event)">
    <br>
    <img id="preview" class="preview-img" src="<?php
        $jpgPath = "uploads/profile" . $student_id . ".jpg";
        $pngPath = "uploads/profile" . $student_id . ".png";
        if ($studentData['photo'] == 1 && (file_exists($jpgPath) || file_exists($pngPath))) {
            echo file_exists($jpgPath) ? $jpgPath : $pngPath;
        } else {
            echo "uploads/default_photo.jpg";
        }
    ?>" alt="Preview">
    <br>
    <button type="submit" name="reset_photo">Reset to Default</button>
    <br><br>

    <!-- Phone Number -->
    <label>Phone Number:</label>
    <input type="text" name="phone" value="<?php echo $studentData['phone_number']; ?>"><br><br>

    <!-- Resume Upload -->
    <label>Upload Resume (PDF):</label>
    <input type="file" name="profile_cv" accept=".pdf"><br><br>

    <!-- Address Fields -->
    <label>House No / Building Name:</label>
    <input type="text" name="house" value="<?php echo $userData['House_No_Building_Name']; ?>"><br>

    <label>City:</label>
    <input type="text" name="city" value="<?php echo $userData['City']; ?>"><br>

    <label>State:</label>
    <input type="text" name="state" value="<?php echo $userData['State']; ?>"><br>

    <label>Pincode:</label>
    <input type="text" name="pincode" value="<?php echo $userData['pincode']; ?>"><br><br>

    <input type="submit" value="Update Profile">
</form>

</body>
</html>
