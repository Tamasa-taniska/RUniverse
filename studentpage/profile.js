// Function to update the photo preview
function updatePhoto() {
    const photoInput = document.getElementById('upload');
    const photo = document.getElementById('profile-photo');
    const file = photoInput.files[0];
    const reader = new FileReader();
    reader.onloadend = function() {
        photo.src = reader.result;
    }
    if (file) {
        reader.readAsDataURL(file);
    }
}

// Function to show the edit form and hide profile info
function editProfile() {
    // Hide profile info and show edit form
    document.querySelector('.container').style.display = 'none';
    document.getElementById('edit-form').style.display = 'block';
}

// Function to save the changes
function saveChanges() {
    document.getElementById('name').textContent = document.getElementById('edit-name').value;
    document.getElementById('mobile').textContent = document.getElementById('edit-mobile').value;
    document.getElementById('DOB').textContent = document.getElementById('edit-DOB').value;
    document.getElementById('email').textContent = document.getElementById('edit-email').value;
    document.getElementById('position').textContent = document.getElementById('edit-position').value;
    document.getElementById('address').textContent = document.getElementById('edit-address').value;
    document.getElementById('district').textContent = document.getElementById('edit-district').value;
    document.getElementById('state').textContent = document.getElementById('edit-state').value;
    document.getElementById('pincode').textContent = document.getElementById('edit-pincode').value;
    // Hide the edit form and show the profile section again
    cancelEdit();
}
// Function to cancel the editing and return to profile view
function cancelEdit() {
    document.getElementById('edit-form').style.display = 'none';
    document.querySelector('.container').style.display = 'flex';
}
function logout(){
    window.location.href='login.html';
}
function studymaterials(){
    window.location.href='StudyMaterials.html';
}
function compose(){
    window.location.href='compose.html';
}