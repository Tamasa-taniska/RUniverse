<?php
include 'db_config_faculty_allotment.php';

$assignments = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];

    $sql = "SELECT * FROM course_allotment WHERE faculty_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $assignments[$row['course']][$row['sem']][$row['sub']] = $row['faculty_id'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Allotment System</title>
    <style>
        body {
      font-family: 'Segoe UI', sans-serif;
      background-color: white;
      padding: 40px;
    }

    .container {
      max-width: 750px;
      margin: auto;
      background-color:rgba(138, 133, 133, 0.308);
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
    <div class="container">
        <h2>Subject-Wise Faculty Allotment</h2>

        <label for="course">Select Course:</label>
        <select id="course" onchange="loadSemesters()">
            <option value="">--Select Course--</option>
            <option value="bscitm">BSc(ITM)</option>
            <option value="bca">BCA</option>
            <option value="bba">BBA</option>
            <option value="mca">MCA</option>
            <option value="mscit">MSc(IT)</option>
        </select>

        <label for="semester">Select Semester:</label>
        <select id="semester" onchange="loadSubjects()">
            <option value="">--Select Semester--</option>
        </select>

        <div id="subjectList"></div>

        <button class="submit-btn" onclick="submitAllotment()">Submit Allotment</button>
    </div>

    <script>
        const data = {
            bscitm: {
                sem1: ['Digital Logic', 'Programming in C', 'Discrete Mathematics', 'Environmental Science'],
                sem2: ['Computer Organization', 'Data Structure', 'Numerical Techniques', 'MIL'],
                sem3: ['Programming in C++', 'Database Management System', 'Principle of Management', 'Theory of Computation', 'Communicative Engish'],
                sem4: ['JAVA', 'Business Accounting', 'Operating Systems', 'Quality Assurance & Testing', 'Quantitative Aptitude & Logical Reasoning'],
                sem5: ['Web Technologies', 'Software Engineering', 'Computer Network & Security', 'Organizational Behaviour'],
                sem6: ['computer Networks', 'Management Accounting', 'Marketing Management']
            },
            bca: {
                sem1: ['Fundamentals of Computers', 'Mathematics I', 'Programming Principles'],
                sem2: ['Data Structures', 'Database Management', 'Discrete Mathematics'],
                sem3: ['Web Technologies', 'Software Engineering', 'Computer Network & Security'],
                sem4: ['JAVA', 'Operating Systems', 'Quality Assurance & Testing', 'Quantitative Aptitude & Logical Reasoning'],
                sem5: ['Computer Organization', 'Numerical Techniques', 'MIL'],
                sem6: ['C++', 'Digital Logic', 'Computer Networks']
            },
            bba: {
                sem1: ['Principle of Management','Discrete Mathematics', 'Environmental Science'],
                sem2: ['Organizational Behaviour', 'Numerical Techniques', 'MIL'],
                sem3: ['Principle of Management-II', 'Theory of Computation', 'Communicative Engish'],
                sem4: ['Business Accounting', 'Quantitative Aptitude & Logical Reasoning'],
                sem5: ['Marketing Management', 'Financial Services'],
                sem6: [ 'Management Accounting', 'E-Commerce', 'Brand Management']
            },
            mca: {
                sem1: ['Fundamentals of Computers', 'Mathematics-I', 'Programming Principles'],
                sem2: ['Data Structures', 'Database Management', 'Mathematics-II'],
                sem3: ['Phython Programming', 'Computer Networks'],
                sem4: ['Android Programmimg', 'Computer Applications']
            },
            mscit: {
                sem1: ['Fundamentals of Computers', 'Mathematics-I', 'Programming Principles'],
                sem2: ['Data Structures', 'Database Management', 'Mathematics-II'],
                sem3: ['Web Development', 'Computer Networks'],
                sem4: ['Full Stack Development', 'Computer Applications']
            }
        };

        // Load assignments from PHP
        const facultyAssignments = <?php echo json_encode($assignments); ?> || {};

        function loadSemesters() {
            const course = document.getElementById('course').value;
            const semesterDropdown = document.getElementById('semester');
            semesterDropdown.innerHTML = '<option value="">--Select Semester--</option>';

            if (course && data[course]) {
                Object.keys(data[course]).forEach(sem => {
                    semesterDropdown.innerHTML += `<option value="${sem}">${sem.toUpperCase()}</option>`;
                });
            }

            document.getElementById('subjectList').innerHTML = '';
        }

        function loadSubjects() {
            const course = document.getElementById('course').value;
            const semester = document.getElementById('semester').value;
            const subjectDiv = document.getElementById('subjectList');
            subjectDiv.innerHTML = '';

            if (course && semester && data[course] && data[course][semester]) {
                data[course][semester].forEach((subject, index) => {
                    const assigned = (facultyAssignments[course] && facultyAssignments[course][semester] && facultyAssignments[course][semester][subject]) || '';
                    subjectDiv.innerHTML += `
                        <div class="subject-block" id="block_${index}">
                            <strong>${subject}</strong><br/>
                            <input type="text" id="faculty_${index}" value="${assigned}" placeholder="Enter Faculty ID" />
                            <div class="buttons">
                                <button onclick="updateFaculty('${course}', '${semester}', '${subject}', ${index})" >Update</button>
                                <button onclick="deleteFaculty('${course}', '${semester}', '${subject}', ${index})">Delete</button>
                            </div>
                        </div>
                    `;
                });
            }
        }

        function updateFaculty(course, semester, subject, index) {
            const fid = document.getElementById(`faculty_${index}`).value.trim();
            if (fid) {
                // Update local object
                if (!facultyAssignments[course]) facultyAssignments[course] = {};
                if (!facultyAssignments[course][semester]) facultyAssignments[course][semester] = {};
                facultyAssignments[course][semester][subject] = fid;

                // Send to server
                fetch('update_assignment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        course: course,
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

        function deleteFaculty(course, semester, subject, index) {
            if (!confirm(`Are you sure you want to remove faculty assignment for ${subject}?`)) return;

            // Update local object
            if (facultyAssignments[course] && facultyAssignments[course][semester] && facultyAssignments[course][semester][subject]) {
                delete facultyAssignments[course][semester][subject];
            }
            document.getElementById(`faculty_${index}`).value = "";

            // Send to server
            fetch('delete_assignment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    course: course,
                    sem: semester,
                    sub: subject
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
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

