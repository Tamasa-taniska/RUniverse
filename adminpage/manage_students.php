<?php
// include 'db_connection.php';
include("../studentpage/dbconnect.php");

if (isset($_POST['graduate'])) {
    $query = "UPDATE students SET status = 'Graduated' WHERE semester = 6";
    mysqli_query($data, $query);
    echo "Graduation process completed!";
}

mysqli_close($data);
?>
