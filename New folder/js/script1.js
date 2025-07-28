// Load sidebar dynamically
document.addEventListener("DOMContentLoaded", function () {
    fetch("sidebar.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("sidebar-container").innerHTML = data;
        })
        .catch(error => console.error("Error loading sidebar:", error));

    // Load Freelancer Profile Data from LocalStorage
    if (localStorage.getItem("freelancerData")) {
        const profile = JSON.parse(localStorage.getItem("freelancerData"));
        document.getElementById("name").textContent = profile.name || "Not Set";
        document.getElementById("email").textContent = profile.email || "Not Set";
        document.getElementById("skills").textContent = profile.skills || "Not Set";
    }
});

// Save Freelancer Data
function saveProfile() {
    const freelancerData = {
        name: document.getElementById("inputName").value,
        email: document.getElementById("inputEmail").value,
        skills: document.getElementById("inputSkills").value,
    };
    localStorage.setItem("freelancerData", JSON.stringify(freelancerData));
    alert("Profile Updated!");
}