<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    session_destroy();
    header("location: ../studentpage/login.php");
    exit();
}
include("../../studentpage/dbconnect.php");

$email = $_SESSION['email'];

// Fetch courses from database
$courses = [];
$stmt = $data->prepare("SELECT course_id, course_name FROM courses");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

$assignments = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];

    $sql = "SELECT ca.*, c.course_name 
            FROM course_allotment ca
            JOIN courses c ON ca.course_id = c.course_id
            WHERE ca.faculty_id = ?";
    $stmt = $data->prepare($sql);
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $assignments[$row['course_id']][$row['sem']][$row['sub']] = $row['faculty_id'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Allotment System</title>
    <style>
        /* Keep the same CSS styles as before */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: white;
            padding: 40px;
        }
        .container {
            max-width: 750px;
            margin: auto;
            background-color: rgba(138, 133, 133, 0.308);
            padding: 25px;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        select, input {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .subject-block {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            position: relative;
        }
        .subject-block input {
            width: 60%;
            padding: 6px;
        }
        .buttons {
            position: absolute;
            right: 15px;
            top: 15px;
        }
        .buttons button {
            margin-left: 5px;
            padding: 6px 10px;
            font-size: 14px;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #c72727;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #1f1e1eab;
        }
    </style>
</head>
<body>
<div id="header-placeholder"></div>
<script>
    fetch('../a_header.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        });
</script>

<div class="container">
    <h2>Subject-Wise Faculty Allotment</h2>

    <label for="course">Select Course:</label>
    <select id="course" onchange="loadSubjects()">
        <option value="">--Select Course--</option>
        <?php foreach ($courses as $course): ?>
            <option value="<?= $course['course_id'] ?>"><?= htmlspecialchars($course['course_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="semester">Select Semester:</label>
    <select id="semester" onchange="loadSubjects()">
        <option value="">--Select Semester--</option>
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select>

    <div id="subjectList"></div>

    <button class="submit-btn" onclick="submitAllotment()">Submit Allotment</button>
</div>

<script>
    const facultyAssignments = <?= json_encode($assignments) ?> || {};

    async function loadSubjects() {
        const courseId = document.getElementById('course').value;
        const semester = document.getElementById('semester').value;
        const subjectDiv = document.getElementById('subjectList');
        subjectDiv.innerHTML = '';

        if (courseId && semester) {
            try {
                const response = await fetch('get_subjects.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `course_id=${courseId}&semester=${semester}`
                });
                
                const subjects = await response.json();
                
                if (subjects.length > 0) {
                    subjects.forEach((subject, index) => {
                        const assigned = (facultyAssignments[courseId] && 
                                       facultyAssignments[courseId][semester] && 
                                       facultyAssignments[courseId][semester][subject.subject_name]) || '';
                        subjectDiv.innerHTML += `
                            <div class="subject-block" id="block_${index}">
                                <strong>${subject.subject_name}</strong><br/>
                                <input type="text" id="faculty_${index}" 
                                       value="${assigned}" 
                                       placeholder="Enter Faculty ID" />
                                <div class="buttons">
                                    <button onclick="updateFaculty(${courseId}, ${semester}, '${subject.subject_name.replace(/'/g, "\\'")}', ${index})">
                                        Update
                                    </button>
                                    <button onclick="deleteFaculty(${courseId}, ${semester}, '${subject.subject_name.replace(/'/g, "\\'")}', ${index})">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    subjectDiv.innerHTML = '<p>No subjects found for this semester.</p>';
                }
            } catch (error) {
                console.error('Error fetching subjects:', error);
                subjectDiv.innerHTML = '<p>Error loading subjects. Please try again.</p>';
            }
        }
    }

    function updateFaculty(courseId, semester, subject, index) {
        const fid = document.getElementById(`faculty_${index}`).value.trim();
        if (fid) {
            fetch('update_assignment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    course_id: courseId,
                    sem: semester,
                    sub: subject,
                    fid: fid
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Faculty ID for "${subject}" updated to "${fid}"`);
                } else {
                    alert(data.message || "Error updating assignment");
                }
            })
            .catch(error => {
                alert("Error updating assignment");
                console.error(error);
            });
        } else {
            alert("Please enter a valid Faculty ID.");
        }
    }

    function deleteFaculty(courseId, semester, subject, index) {
        if (!confirm(`Are you sure you want to remove faculty assignment for ${subject}?`)) return;

        fetch('delete_assignment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                course_id: courseId,
                sem: semester,
                sub: subject
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`faculty_${index}`).value = "";
                alert(`Faculty assignment removed for "${subject}"`);
            } else {
                alert(data.message || "Error deleting assignment");
            }
        })
        .catch(error => {
            alert("Error deleting assignment");
            console.error(error);
        });
    }

    function submitAllotment() {
        const course = document.getElementById('course').value;
        const semester = document.getElementById('semester').value;
        if (!course || !semester) {
            alert("Please select both course and semester.");
            return;
        }
        alert("All assignments have been saved as you updated them.");
    }
</script>
</body>
</html>