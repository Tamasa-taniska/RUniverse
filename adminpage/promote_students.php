<?php
// include 'db_connection.php'; 
include("../studentpage/dbconnect.php");

$query = "UPDATE students SET semester = semester + 1 WHERE semester IN (1, 3, 5)";
if (mysqli_query($data, $query)) {
    echo "Students successfully promoted!";
} else {
    echo "Error updating semesters: " . mysqli_error($data);
}

mysqli_close($data);
?>
