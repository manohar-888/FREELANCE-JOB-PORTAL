<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "freelance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user_id = $_SESSION['user_id'];
$skills = $_POST['skills'];
$experience = $_POST['experience'];

// Insert profile into database
$sql = "INSERT INTO freelancer_profiles (user_id, skills, experience) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $skills, $experience);

if ($stmt->execute()) {
    $_SESSION['message'] = "Profile created successfully!";
    header("Location: freelancer_dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
