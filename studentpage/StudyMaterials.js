// Array to store uploaded materials (in-memory only)
let uploadedMaterials = [];
// Handle form submission
document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const semester = document.getElementById('semester').value;
    const subject = document.getElementById('subject').value;
    const file = document.getElementById('file').files[0];

    if (semester && subject && file) {
        // Create a file URL (simulate file storage)
        const fileURL = URL.createObjectURL(file);

        // Add the material to the array
        uploadedMaterials.push({
            semester: semester,
            subject: subject,
            fileName: file.name,
            fileURL: fileURL
        });

        // Display the uploaded material
        displayUploadedMaterials();

        // Reset the form
        document.getElementById('uploadForm').reset();
    } else {
        alert('Please fill all fields and select a file.');
    }
});

// Function to display uploaded materials
function displayUploadedMaterials() {
    const uploadedList = document.getElementById('uploadedList');
    uploadedList.innerHTML = ''; // Clear the list

    uploadedMaterials.forEach((material, index) => {
        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <strong>Semester:</strong> ${material.semester}<br>
            <strong>Subject:</strong> ${material.subject}<br>
            <strong>File:</strong> <a href="${material.fileURL}" target="_blank">${material.fileName}</a>
        `;
        uploadedList.appendChild(listItem);
    });
}