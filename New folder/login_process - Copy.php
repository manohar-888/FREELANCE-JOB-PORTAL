<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and handles database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query database for user
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['email']; // Store user session
            $_SESSION['message'] = "Login successful!";
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password!";
        }
    } else {
        $_SESSION['message'] = "User not found!";
    }

    header("Location: login.php"); // Redirect back to login page
    exit();
} else {
    $_SESSION['message'] = "Invalid request!";
    header("Location: login.php");
    exit();
}
?>
