<?php include('../../studentpage/dbconnect.php'); ?>
<!DOCTYPE html>
<html>
<head>

    <title>Upload Semester Marks</title>
    <link rel="stylesheet" href="/ravenshaw/adminpage/assets/ustyle.css">

    <script>
        // Load the header content after the DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/ravenshaw/adminpage/a_header.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('header-placeholder').innerHTML = data;
                });
        });

        function fetchSubjectsAndStudents() {
            let course = document.getElementById('course').value;
            let semester = document.getElementById('semester').value;
            let batch = document.getElementById('batch').value;

            if (course && semester && batch) {
                fetch("/ravenshaw/adminpage/admin/get-students.php?course=" + course + "&batch=" + batch)
                    .then(res => res.text())
                    .then(html => document.getElementById('student_id').innerHTML = html);

                fetch("/ravenshaw/adminpage/admin/get-subjects.php?course=" + course + "&semester=" + semester)
                    .then(res => res.text())
                    .then(html => document.getElementById('subjects-table').innerHTML = html);
            }
        }

        function calculateResults() {
            let totalCredits = 0;
            let totalPoints = 0;
            let passed = true;

            document.querySelectorAll('.subject-row').forEach(row => {
                let marks = parseInt(row.querySelector('.marks').value || 0);
                let pass = parseInt(row.dataset.pass);
                let max = parseInt(row.dataset.max);
                let resultCell = row.querySelector('.result');

                if (marks >= pass) {
                    resultCell.innerText = 'Pass';
                } else {
                    resultCell.innerText = 'Fail';
                    passed = false;
                }

                let sgpa = (marks / max) * 10;
                row.querySelector('.sgpa').value = sgpa.toFixed(2);

                totalPoints += sgpa;
                totalCredits++;
            });

            let finalSgpa = (totalPoints / totalCredits).toFixed(2);
            document.getElementById('final_sgpa').value = finalSgpa;
            document.getElementById('overall_result').value = passed ? 'Pass' : 'Fail';
        }
    </script>

</head>
<body>
<div id="header-placeholder"></div>
<div class="container">
<h2>Upload Semester Marks</h2>
<form method="POST" action="/ravenshaw/adminpage/admin/process-marks.php">

    <label>Course:</label>
    <select name="course_id" id="course" onchange="fetchSubjectsAndStudents()" required>
        <option value="">Select Course</option>
        <?php
        $result = $data->query("SELECT * FROM courses");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Batch:</label>
    <select name="batch" id="batch" onchange="fetchSubjectsAndStudents()" required>
        <option value="">Select Batch</option>
        <option>2022</option>
        <option>2023</option>
        <option>2024</option>
    </select><br><br>

    <label>Semester:</label>
    <select name="semester" id="semester" onchange="fetchSubjectsAndStudents()" required>
        <option value="">Select Semester</option>
        <?php for ($i = 1; $i <= 6; $i++) echo "<option value='$i'>Semester $i</option>"; ?>
    </select><br><br>

    <label>Student:</label>
    <select name="student_id" id="student_id" required>
        <option value="">Select Student</option>
    </select><br><br>

    <div id="subjects-table"></div>

    <br>
    <input type="button" value="Calculate SGPA & Result" onclick="calculateResults()"><br><br>

    <label>Final SGPA:</label> <input type="text" name="sgpa" id="final_sgpa" readonly><br>
    <label>Overall Result:</label> <input type="text" name="overall_result" id="overall_result" readonly><br><br>

    <input type="submit" value="Submit All Marks">
</form>
</div>
</body>
</html>
