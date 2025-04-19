<!DOCTYPE html>                                                                                                                                          <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compose Email</title>
    <link rel="stylesheet" href="studentStyles.css">
</head>
<body>
    <div id="header-placeholder"></div>
    <script>
        // Load the header content from header.html
        fetch('header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>
<div class="notes-body">
    <div class="notes">
        <h1>Notes</h1>
        <a href="view_notes.php" style="background-color:#a72222; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">Go to Notes Portal</a>
        <div id="semester-selection">
            <label for="semester">What semester are you in?</label>
            <br><br>
            <select id="semester" onchange="showSubjects()">
                <option value="">Select Semester</option>
                <option value="semester1">Semester 1</option>
                <option value="semester2">Semester 2</option>
                <option value="semester3">Semester 3</option>
                <option value="semester4">Semester 4</option>
                <option value="semester5">Semester 5</option>
                <option value="semester6">Semester 6</option>
            </select>
        </div>
        <div id="subjects" class="hidden">
            <h2>Subjects</h2>
            <ul id="subjects-list"></ul>
        </div>
        <div id="notes" class="hidden">
            <h2>Notes</h2>
            <ul id="notes-list"></ul>
        </div>
    </div>
</div>
</div>
    <!-- <script src="scripts.js"></script> -->
</body>
</html>                                     
