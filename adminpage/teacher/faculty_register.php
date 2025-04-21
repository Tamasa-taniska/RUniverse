<?php
require '../../studentpage/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? NULL;
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $designation = $_POST['designation'];
    $pincode = $_POST['pincode'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $house_number = $_POST['house_number'];
    $road_name = $_POST['road_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $photo = 0; // Default photo placeholder

    // Step 1: Insert into users table
    $sql_user = "INSERT INTO users (first_name, middle_name, last_name, role, pincode, State, City, House_No_Building_Name, Road_Name_Area_Colony)
                 VALUES (?, ?, ?, 'Faculty', ?, ?, ?, ?, ?)";

    $stmt_user = $data->prepare($sql_user);
    $stmt_user->bind_param("ssssssss", $first_name, $middle_name, $last_name, $pincode, $state, $city, $house_number, $road_name);


    if ($stmt_user->execute()) {
        // Get the auto-generated user_id
        $user_id = $data->insert_id;
        $sql_faculty_update = "UPDATE faculty 
        SET designation = ?, phone_number = ?, email = ?, password = ?, DOB = ?, photo = ? 
        WHERE faculty_id = ?";

        $stmt_faculty = $data->prepare($sql_faculty_update);
        if (!$stmt_faculty) {
            die("Prepare failed: " . $data->error);
        }

        $stmt_faculty->bind_param("ssssisi", $designation, $phone_number, $email, $password, $dob, $photo, $faculty_id);


        if ($stmt_faculty->execute()) {
            $message = "Teacher registered successfully with user ID: $user_id";
            echo "<script>alert(" . json_encode($message) . ");
            window.location.href = '../adminhome.php';
            </script>";
            

        } else {
            echo "Error updating Teacher table: " . $stmt_faculty->error;
        }

        $stmt_faculty->close();
    } else {
        echo "Error inserting into users table: " . $stmt_user->error;
    }

    $stmt_user->close();
    $data->close();
}

