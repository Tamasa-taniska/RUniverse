<?php
require_once 'db_config_faculty_allotment.php';
include 'dbconnect.php';

// Initialize variables
$faculty_id = '';
$assignments = [];
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_id = trim($_POST['faculty_id'] ?? '');

    if (!empty($faculty_id)) {
        try {
            $stmt = $conn->prepare("SELECT course, sem, sub FROM course_allotment WHERE faculty_id = ? ORDER BY course, sem");
            $stmt->bind_param("i", $faculty_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $assignments = [];

          while ($row = $result->fetch_assoc()) {
             $assignments[] = $row;
            }

            if (empty($assignments)) {
                $error = "No subjects assigned for Faculty ID: " . htmlspecialchars($faculty_id);
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a Faculty ID";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Allotted Subjects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #c72727;
            text-align: center;
        }

        .faculty-input {
            background-color: #eef4f2;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #c72727;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1f1e1eab;
        }

        .error {
            color: #d9534f;
            padding: 12px;
            background: #ffecec;
            margin-bottom: 20px;
            border-left: 4px solid #d9534f;
        }

        table.assignment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.assignment-table th, table.assignment-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table.assignment-table th {
            background-color: #c72727;
            color: white;
        }

        table.assignment-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Faculty Subject Allotments</h1>

        <div class="faculty-input">
          <form method="POST">
               <label for="faculty_id">Enter Your Faculty ID:</label>
               <input type="text" id="faculty_id" name="faculty_id" required>
               <button type="submit">View Subjects</button>
          </form>

        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($faculty_id) && empty($error)): ?>
            <h2>Assigned Subjects for Faculty ID: <?php echo htmlspecialchars($faculty_id); ?></h2>

            <?php
            $grouped = array_reduce($assignments, function ($result, $item) {
                $key = $item['course'] . '|' . $item['sem'];
                $result[$key][] = $item;
                return $result;
            }, []);
            ?>

            <?php foreach ($grouped as $key => $rows): ?>
                <?php list($course, $sem) = explode('|', $key); ?>
                <h3><?php echo strtoupper($course); ?> - SEMESTER <?php echo strtoupper($sem); ?></h3>
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $assignment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(strtoupper($assignment['course'])); ?></td>
                                <td><?php echo htmlspecialchars(strtoupper($assignment['sem'])); ?></td>
                                <td><?php echo htmlspecialchars(strtoupper($assignment['sub'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
