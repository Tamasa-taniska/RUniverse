document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            event.preventDefault();
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var errorMessage = document.getElementById("errorMessage");

            // Replace these with actual valid credentials
            var validUsername = "student123";
            var validPassword = "password123";

            if (username === validUsername && password === validPassword) {
                alert("Login successful!");
                window.location.href = "profile.html"; 
            } else {
                errorMessage.textContent = "Invalid username or password. Please try again.";
            }
        });
    }

    // Logout function
    function logout() {
        sessionStorage.clear();
        window.location.href = "index.html";
        alert('Logged out successfully!');
    }

    // Logout button
    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', logout);
    }

    // Change password button
    const changePasswordButton = document.getElementById('changePassword');
    if (changePasswordButton) {
        changePasswordButton.addEventListener('click', function() {
            const newPassword = prompt('Please enter your new password:');
            if (newPassword) {
                alert('Your password has been changed successfully.');
                // Here you would typically send the new password to the server
            } else {
                alert('Password change cancelled.');
            }
        });
    }

    // Edit Profile function
    function editProfile() {
        document.getElementById("editProfileModal").style.display = "block";

        // Fill the form with current profile details
        document.getElementById("editStudentName").value = document.getElementById("studentName").textContent.replace("Student Name: ", "");
        document.getElementById("editDateOfBirth").value = "2000-01-01"; // Adjust to your date format
        document.getElementById("editAddress").value = document.getElementById("address").textContent.replace("Address: ", "");
        document.getElementById("editCity").value = document.getElementById("city").textContent.replace("City: ", "");
        document.getElementById("editState").value = document.getElementById("state").textContent.replace("State: ", "");
        document.getElementById("editMobile").value = document.getElementById("mobile").textContent.replace("Mobile: ", "");
        document.getElementById("editCourse").value = document.getElementById("course").textContent.replace("Course: ", "");
        document.getElementById("editBranch").value = document.getElementById("branch").textContent.replace("Branch: ", "");
        document.getElementById("editBloodGroup").value = document.getElementById("bloodGroup").textContent.replace("Blood Group: ", "");
        document.getElementById("editRollNo").value = document.getElementById("rollNo").textContent.replace("Roll No: ", "");
        document.getElementById("editMotherName").value = document.getElementById("motherName").textContent.replace("Mother Name: ", "");
        document.getElementById("editDistrict").value = document.getElementById("district").textContent.replace("District: ", "");
        document.getElementById("editPinCode").value = document.getElementById("pinCode").textContent.replace("Pin Code: ", "");
        document.getElementById("editEmail").value = document.getElementById("email").textContent.replace("EmailID: ", "");
    }

// Close Edit Profile Modal
function closeEditProfileModal() {
    document.getElementById("editProfileModal").style.display = "none";
}

// Save Changes in Edit Profile Modal
document.getElementById("editProfileForm").addEventListener("submit", function(event) {
    event.preventDefault();

    // Update profile details with form data
    document.getElementById("studentName").textContent = "Student Name: " + document.getElementById("editStudentName").value;
    document.getElementById("dateOfBirth").textContent = "Date of Birth: " + document.getElementById("editDateOfBirth").value;
    document.getElementById("address").textContent = "Address: " + document.getElementById("editAddress").value;
    document.getElementById("city").textContent = "City: " + document.getElementById("editCity").value;
    document.getElementById("state").textContent = "State: " + document.getElementById("editState").value;
    document.getElementById("mobile").textContent = "Mobile: " + document.getElementById("editMobile").value;
    document.getElementById("course").textContent = "Course: " + document.getElementById("editCourse").value;
    document.getElementById("branch").textContent = "Branch: " + document.getElementById("editBranch").value;
    document.getElementById("bloodGroup").textContent = "Blood Group: " + document.getElementById("editBloodGroup").value;
    document.getElementById("rollNo").textContent = "Roll No: " + document.getElementById("editRollNo").value;
    document.getElementById("motherName").textContent = "Mother Name: " + document.getElementById("editMotherName").value;
    document.getElementById("district").textContent = "District: " + document.getElementById("editDistrict").value;
    document.getElementById("pinCode").textContent = "Pin Code: " + document.getElementById("editPinCode").value;
    document.getElementById("email").textContent = "EmailID: " + document.getElementById("editEmail").value;

    // Show alert message for saving changes
    alert("Profile changes have been saved successfully!");

    // Close the modal
    closeEditProfileModal();
});


    // Make functions globally accessible
    window.editProfile = editProfile;
    window.closeEditProfileModal = closeEditProfileModal;
});

document.addEventListener("DOMContentLoaded", function() {
    const subjects = {
        semester1: ["Mathematics I", "Physics I", "Chemistry", "Introduction to Programming", "Engineering Graphics"],
        semester2: ["Mathematics II", "Physics II", "Environmental Studies", "Data Structures", "Basic Electronics"],
        semester3: ["Mathematics III", "Computer Organization", "Discrete Mathematics", "Digital Logic Design", "Object-Oriented Programming"],
        semester4: ["Probability and Statistics", "Database Systems", "Operating Systems", "Computer Networks", "Software Engineering"],
        semester5: ["Algorithm Design", "Theory of Computation", "Web Technologies", "Artificial Intelligence", "Elective I"],
        semester6: ["Compiler Design", "Data Mining", "Distributed Systems", "Information Security", "Elective II"],
    };

    const notesLinks = {
        "Mathematics I": "notes/MathematicsI.pdf",
        "Physics I": "notes/PhysicsI.pdf",
        "Chemistry": "notes/Chemistry.pdf",
        "Introduction to Programming": "notes/IntroductionToProgramming.pdf",
        "Engineering Graphics": "notes/EngineeringGraphics.pdf",
        "Mathematics II": "notes/MathematicsII.pdf",
        "Physics II": "notes/PhysicsII.pdf",
        "Environmental Studies": "notes/EnvironmentalStudies.pdf",
        "Data Structures": "notes/DataStructures.pdf",
        "Basic Electronics": "notes/BasicElectronics.pdf",
        "Mathematics III": "notes/MathematicsIII.pdf",
        "Computer Organization": "notes/ComputerOrganization.pdf",
        "Discrete Mathematics": "notes/DiscreteMathematics.pdf",
        "Digital Logic Design": "notes/DigitalLogicDesign.pdf",
        "Object-Oriented Programming": "notes/ObjectOrientedProgramming.pdf",
        "Probability and Statistics": "notes/ProbabilityAndStatistics.pdf",
        "Database Systems": "notes/DatabaseSystems.pdf",
        "Operating Systems": "notes/OperatingSystems.pdf",
        "Computer Networks": "notes/ComputerNetworks.pdf",
        "Software Engineering": "notes/SoftwareEngineering.pdf",
        "Algorithm Design": "notes/AlgorithmDesign.pdf",
        "Theory of Computation": "notes/TheoryOfComputation.pdf",
        "Web Technologies": "notes/WebTechnologies.pdf",
        "Artificial Intelligence": "notes/ArtificialIntelligence.pdf",
        "Elective I": "notes/ElectiveI.pdf",
        "Compiler Design": "notes/CompilerDesign.pdf",
        "Data Mining": "notes/DataMining.pdf",
        "Distributed Systems": "notes/DistributedSystems.pdf",
        "Information Security": "notes/InformationSecurity.pdf",
        "Elective II": "notes/ElectiveII.pdf",
    };

    function showSubjects() {
        const semester = document.getElementById("semester").value;
        const subjectsList = document.getElementById("subjects-list");
        subjectsList.innerHTML = "";

        if (semester && subjects[semester]) {
            subjects[semester].forEach(subject => {
                const li = document.createElement("li");
                li.textContent = subject;
                li.setAttribute("onclick", `showNotes("${subject}")`);
                subjectsList.appendChild(li);
            });

            document.getElementById("subjects").classList.remove("hidden");
        } else {
            document.getElementById("subjects").classList.add("hidden");
        }

        document.getElementById("notes").classList.add("hidden");
    }

    function showNotes(subject) {
        const notesList = document.getElementById("notes-list");
        notesList.innerHTML = "";

        if (notesLinks[subject]) {
            const li = document.createElement("li");
            const a = document.createElement("a");
            a.href = notesLinks[subject];
            a.textContent = "View Notes";
            a.target = "_blank";
            li.appendChild(a);
            notesList.appendChild(li);

            document.getElementById("notes").classList.remove("hidden");
        } else {
            document.getElementById("notes").classList.add("hidden");
        }
    }

    window.showSubjects = showSubjects;
    window.showNotes = showNotes;
});

