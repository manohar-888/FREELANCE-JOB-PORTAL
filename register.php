<?php
session_start();
require 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all fields are filled
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['role'])) {
        die("Error: Name, Email, Password, or Role is missing.");
    }

    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, trim($_POST['role']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Ensure database connection is established
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check if 'login' table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'login'");
    if ($checkTable === false) {
        die("Error checking table existence: " . $conn->error);
    }
    if ($checkTable->num_rows == 0) {
        die("Error: The table 'login' does not exist. Please check your database.");
    }

    // Check if the user already exists in the database
    $checkUser = $conn->prepare("SELECT id FROM login WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $checkUser->store_result();

    if ($checkUser->num_rows > 0) {
        // User already exists, redirect to login page
        $_SESSION['success'] = "You are already registered. Please log in.";
        $checkUser->close();
        header("Location: login.php");
        exit();
    }

    $checkUser->close();

    // Insert new user into 'login' table
    $sql = "INSERT INTO login (name, email, password, role) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please log in.";
            $stmt->close();
            $conn->close();
            header("Location: login.php");
            exit();
        } else {
            die("Error inserting data: " . $stmt->error);
        }
    } else {
        die("Error preparing statement: " . $conn->error);
    }
} else {
    die("Invalid request method.");
}
?>
