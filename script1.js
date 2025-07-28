document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent page reload

    let formData = new FormData(this);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debugging: Check response in console

        if (data.status === "success") {
            alert("Login Successful!");

            // Redirect based on role
            if (data.role === "freelancer") {
                window.location.href = "freelancer_dashboard.html";
            } else if (data.role === "client") {
                window.location.href = "client_dashboard.html";
            } else {
                window.location.href = "dashboard.html";
            }
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Fetch Error:", error));
});
