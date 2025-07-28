<?php
session_start();
require 'config.php'; // Ensure config.php properly sets up $conn

// Check if user is logged in and has a Freelancer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "Freelancer") {
    header("Location: login.php");
    exit();
}

// Debugging: Ensure session values are correct
if (!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
    die("Session Error: Name or Email is missing.");
}

// Handle profile submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_SESSION['name']; 
    $email = $_SESSION['email'];
    $skills = htmlspecialchars($_POST['skills']);
    $experience = htmlspecialchars($_POST['experience']);
    $user_id = $_SESSION['user_id'];

    // Ensure User ID exists
    if (!$user_id) {
        die("Error: User ID is missing from the session.");
    }

    // Insert or update freelancer profile
    $sql = "INSERT INTO freelancer_profiles (user_id, name, email, skills, experience) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE skills = VALUES(skills), experience = VALUES(experience)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $name, $email, $skills, $experience);

    if ($stmt->execute()) {
        $_SESSION['profile_completed'] = true;
        header("Location: freelancer_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Profile</title>
    <style>
        /* General styles */
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
            min-height: 80vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 50%;
            text-align: center;
            margin-top: 50px;
        }

        h2 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
        }

        input, textarea {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            width: 100%;
        }

        textarea {
            resize: vertical;
            height: 50px;
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

        .readonly {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>FREELANCE JOB PORTAL</h1>
    </header>

    <main>
        <div class="container">
            <h2>Create Freelancer Profile</h2>
            <form action="freelancer_profile.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" class="readonly" readonly>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" class="readonly" readonly>

                <label for="skills">Skills:</label>
                <textarea id="skills" name="skills" required placeholder="E.g., Web Development, Graphic Design"></textarea>

                <label for="experience">Experience:</label>
                <textarea id="experience" name="experience" required placeholder="Describe your past work experience..."></textarea>

                <button type="submit">Save Profile</button>
            </form>
        </div>
    </main>

</body>
</html>
