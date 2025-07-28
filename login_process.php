<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        if ($user['role'] === 'Freelancer') {
            $profile_check = "SELECT * FROM freelancer_profiles WHERE user_id = ?";
        } else {
            $profile_check = "SELECT * FROM client_profiles WHERE user_id = ?";
        }

        $stmt = $conn->prepare($profile_check);
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            header("Location: create_profile.php"); // Redirect to profile creation
        } else {
            header("Location: " . ($user['role'] === 'Freelancer' ? "freelancer_dashboard.php" : "client_dashboard.php"));
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
