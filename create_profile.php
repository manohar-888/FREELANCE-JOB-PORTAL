<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config.php'; // Ensure database connection is available

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $job_category = htmlspecialchars($_POST['job_category']);
    $location = htmlspecialchars($_POST['location']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO client_profiles (user_id, name, email, job_category, location) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $name, $email, $job_category, $location);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile created successfully!";
        header("Location: client_dashboard.php"); // Redirect to client dashboard
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Client Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
         /* Header styles */
         header {
            background-color: #4CAF50;
            height: 70px;
            display: flex;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }

        header h1 {
            font-size: 28px;
            color: white;
            margin: 0 auto;
        }

        /* Navigation bar */
        nav {
            background-color: #333;
            padding: 15px;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Main content */
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: 80px auto; /* Increased margin-top to move it further down */
        }
        h2 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
            display: block;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            width: 100%;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
        <h1>FREELANCE JOB PORTAL</h1>
    </header>


    <div class="container">
        <h2>Create Client Profile</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class='alert alert-danger'><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form action="" method="post"> <!-- Form submits to the same page -->
            <label for="name">Full Name:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="job_category">Job Category:</label>
            <input type="text" name="job_category" required>

            <label for="location">Location:</label>
            <input type="text" name="location" required>

            <button type="submit">Create Profile</button>
        </form>
    </div>
</body>
</html>