document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");

    form.addEventListener("submit", function (event) {
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const role = document.getElementById("role").value;

        if (name === "" || email === "" || password === "") {
            alert("Please fill in all fields.");
            event.preventDefault(); // Prevent form submission
        } else {
            alert(`Successfully registered as a ${role}!`);
        }
    });
});
