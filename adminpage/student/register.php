<?php
require '../../studentpage/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $pin_code = $_POST['pin_code'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $house_number = $_POST['house_number'];
    $road_name = $_POST['road_name'];
    $batch= $_POST['batch'];
    $roll_number= $_POST['roll_number'];
    $dob = $_POST['dob'];
    $department = $_POST['department'];
    $course_id = $_POST['course_id'];
    $semester = $_POST['semester'];
    $phone_number = $_POST['phone_number'];
    $blood_group = $_POST['blood_group'];
    $email = $_POST['email'];
    $password= $_POST['password'];

    // Step 1: Insert into users table
    $sql_user = "INSERT INTO users (first_name, middle_name, last_name, role, pincode, State, City, House_No_Building_Name, Road_Name_Area_Colony)
                 VALUES (?, ?, ?, 'Student', ?, ?, ?, ?, ?)";

    $stmt_user = $data->prepare($sql_user);
    $stmt_user->bind_param("ssssssss", $first_name, $middle_name, $last_name, $pin_code, $state, $city, $house_number, $road_name);

    if ($stmt_user->execute()) {
        // Get the auto-generated user_id
        $user_id = $data->insert_id;

        // Step 2: Update relevant fields in the students table
        // $sql_student_update = "UPDATE students 
        //                        SET roll_number= ?, batch= ? dob = ?, department = ?, course_id = ?, semester = ?, phone_number = ?, blood_group = ?, email = ?, password= ?
        //                        WHERE student_id = ?";

        $sql_student_update = "UPDATE students 
   SET roll_number= ?, batch= ?, dob = ?, department = ?, course_id = ?, semester = ?, phone_number = ?, blood_group = ?, email = ?, password = ?
   WHERE student_id = ?";


        $stmt_student = $data->prepare($sql_student_update);
        if (!$stmt_student) {
            die("Prepare failed: " . $data->error);
        }
        
        $stmt_student->bind_param("sississsssi", $roll_number, $batch, $dob, $department, $course_id, $semester, $phone_number, $blood_group, $email, $password , $user_id);

        if ($stmt_student->execute()) {
            // echo "Student registered successfully with user ID: " . $user_id;
            $message = "Student registered successfully with user ID: $user_id";
            echo "<script>alert(" . json_encode($message) . ");
            window.location.href = '../adminhome.php';
            </script>";
            

        } else {
            echo "Error updating student table: " . $stmt_student->error;
        }

        $stmt_student->close();
    } else {
        echo "Error inserting into users table: " . $stmt_user->error;
    }

    $stmt_user->close();
    $data->close();
}
?>
