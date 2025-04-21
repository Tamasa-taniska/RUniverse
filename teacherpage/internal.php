<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "faculty") {
    session_destroy();
    header("location: /ravenshaw/studentpage/login.php");
    exit();
}
$email = $_SESSION['email'];
include '../studentpage/dbconnect.php';

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: text/html');
    
    if ($_POST['action'] == 'get_subjects') {
        $course_id = intval($_POST['course_id']);
        $semester = intval($_POST['semester']);
        
        $result = mysqli_query($data, "SELECT * FROM subjects 
            WHERE course_id = $course_id AND semester = $semester");
        
        echo '<option value="">Select Subject</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['subject_id']}'>{$row['subject_name']}</option>";
        }
        exit();
    }
    
    if ($_POST['action'] == 'fetch_students') {
        $course_id = intval($_POST['course_id']);
        $semester = intval($_POST['semester']);
        $subject_id = intval($_POST['subject_id']);
        
        $result = mysqli_query($data, "SELECT roll_number, student_id FROM students 
            WHERE course_id = $course_id AND semester = $semester ORDER BY roll_number");
        
        if (mysqli_num_rows($result) > 0) {
            echo '<form id="marksForm" onsubmit="saveMarks(event)">';
            echo '<input type="hidden" name="subject_id" value="'.$subject_id.'">';
            echo '<table>';
            echo '<thead><tr><th>Roll Number</th><th>Marks</th></tr></thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>'.$row['roll_number'].'</td>';
                echo '<td><input type="number" name="marks['.$row['student_id'].']" required></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            echo '<input type="submit" value="Save Marks" style="margin-top:15px; padding:10px 20px;">';
            echo '</form>';
        } else {
            echo 'No students found';
        }
        exit();
    }
    
    if ($_POST['action'] == 'save_marks') {
        $subject_id = intval($_POST['subject_id']);
        $marks = $_POST['marks'];
        $errors = [];
        
        foreach ($marks as $student_id => $mark) {
            $student_id = intval($student_id);
            $mark = intval($mark);
            
            $query = "INSERT INTO internal_marks (student_id, subject_id, marks)
                     VALUES ($student_id, $subject_id, $mark)
                     ON DUPLICATE KEY UPDATE marks = $mark";
            
            if (!mysqli_query($data, $query)) {
                $errors[] = "Error saving marks for student ID: $student_id";
            }
        } 
        echo empty($errors) ? "Marks saved successfully!" : implode("<br>", $errors);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internal Marks Entry</title>
    <style>
        /* General Page Styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Header Styling */
h2 {
    text-align: center;
    font-size: 24px;
    color: #a72222;
    margin-bottom: 20px;
    text-transform: uppercase;
}

/* Form Group Styling */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
}

select, button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    outline: none;
}

select:hover, button:hover {
    border-color: #a72222;
}

button {
    background-color: #a72222;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #a72222;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

thead {
    background-color: #a72222;
    color: white;
}

th, td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 10px;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

input[type="number"] {
    width: 100px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Submit Button Styling */
input[type="submit"] {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    background-color: #a72222;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
}

input[type="submit"]:hover {
    background-color: #a72222;
}

    </style>
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('theader.php')
        .then(res => res.text())
        .then(data => document.getElementById('header-placeholder').innerHTML = data);
</script>                                                                                                                                                                                                                                                                                                                                                                                                    
    <div class="container">
        <h2>Internal Marks Entry</h2>
        
        <div class="form-group">
            <select id="semester" onchange="fetchSubjects()">
                <option value="">Select Semester</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?= $i ?>">Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>

            <select id="course" onchange="fetchSubjects()">
                <option value="">Select Course</option>
                <?php
                $result = mysqli_query($data, "SELECT * FROM courses");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
                }
                ?>
            </select>

            <select id="subject">
                <option value="">Select Subject</option>
            </select>

            <button onclick="fetchStudents()">Fetch Students</button>
        </div>

        <div id="studentsTable"></div>
    </div>

    <script>
        function fetchSubjects() {
            const course = document.getElementById('course').value;
            const semester = document.getElementById('semester').value;
            
            if (course && semester) {
                const formData = new FormData();
                formData.append('action', 'get_subjects');
                formData.append('course_id', course);
                formData.append('semester', semester);
                
                fetch(location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('subject').innerHTML = data;
                });
            }
        }

        function fetchStudents() {
            const course = document.getElementById('course').value;
            const semester = document.getElementById('semester').value;
            const subject = document.getElementById('subject').value;
            
            if (course && semester && subject) {
                const formData = new FormData();
                formData.append('action', 'fetch_students');
                formData.append('course_id', course);
                formData.append('semester', semester);
                formData.append('subject_id', subject);
                
                fetch(location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('studentsTable').innerHTML = data;
                });
            }
        }

        function saveMarks(e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById('marksForm'));
            formData.append('action', 'save_marks');
            
            fetch(location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            });
        }
    </script>
</body>
</html>