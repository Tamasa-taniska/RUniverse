
// Handle form submission
document.getElementById('composeForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
});
