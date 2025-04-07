document.addEventListener("DOMContentLoaded", function() {
    // const loginForm = document.getElementById("loginForm");
    // if (loginForm) {
    //     loginForm.addEventListener("submit", function(event) {
    //         event.preventDefault();
    //         var username = document.getElementById("username").value;
    //         var password = document.getElementById("password").value;
    //         var errorMessage = document.getElementById("errorMessage");

    //         // Replace these with actual valid credentials
    //         var validUsername = "student123";
    //         var validPassword = "password123";

    //         if (username === validUsername && password === validPassword) {
    //             alert("Login successful!");
    //             window.location.href = "profile.php"; 
    //         } else {
    //             errorMessage.textContent = "Invalid username or password. Please try again.";
    //         }
    //     });
    // }

    // // Logout function
    // function logout() {
    //     sessionStorage.clear();
    //     window.location.href = "index.html";
    //     alert('Logged out successfully!');
    // }

    // // Logout button
    // const logoutButton = document.getElementById('logoutButton');
    // if (logoutButton) {
    //     logoutButton.addEventListener('click', logout);
    // }

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
            li.classList.add("notes-item"); 
            notesList.appendChild(li);
    
            document.getElementById("notes").classList.remove("hidden");
        } else {
            document.getElementById("notes").classList.add("hidden");
        }
    }
    
    window.showSubjects = showSubjects;
    window.showNotes = showNotes;
});

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}