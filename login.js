document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission to avoid page reload

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const ogUsername="user";
    const ogPassword="pass321"

    if (username===ogUsername  && password===ogPassword) {
        // Redirect to profile
        window.location.href = "profile.html";
    } else {
    alert("Invalid Details");
    }
});
