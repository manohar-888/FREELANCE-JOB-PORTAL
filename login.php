<?php
session_start();
require 'config.php'; // Ensure config.php contains $conn (DB connection)

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$successMessage = "";
$errorMessage = "";

// Check if there's a success message from registration
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']); // Remove message after displaying
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : '';

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Validate input
    if (empty($email) || empty($password) || empty($role)) {
        $errorMessage = "All fields are required!";
    } else {
        // Query to fetch user data
        $sql = "SELECT id, name, email, password, role FROM login WHERE email = ? AND role = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $email, $role);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $name, $db_email, $hashed_password, $db_role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Store session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $db_email; // ✅ Added this to fix session error
                    $_SESSION['role'] = ucfirst(strtolower(trim($db_role)));

                    // Redirect based on user role
                    if ($_SESSION['role'] === 'Freelancer') {
                        $query = "SELECT * FROM freelancer_profiles WHERE user_id = ?";
                        $profile_stmt = $conn->prepare($query);
                        $profile_stmt->bind_param("i", $_SESSION['user_id']);
                        $profile_stmt->execute();
                        $result = $profile_stmt->get_result();

                        if ($result->num_rows > 0) {
                            header("Location: freelancer_dashboard.php");
                        } else {
                            header("Location: freelancer_profile.php"); // ✅ Redirect to profile creation
                        }
                        exit();
                    } elseif ($_SESSION['role'] === 'Client') {
                        $query = "SELECT * FROM client_profiles WHERE user_id = ?";
                        $profile_stmt = $conn->prepare($query);
                        $profile_stmt->bind_param("i", $_SESSION['user_id']);
                        $profile_stmt->execute();
                        $result = $profile_stmt->get_result();

                        if ($result->num_rows > 0) {
                            header("Location: client_dashboard.php");
                        } else {
                            header("Location: create_profile.php");
                        }
                        exit();
                    }
                } else {
                    $errorMessage = "Invalid password!";
                }
            } else {
                $errorMessage = "Invalid email or role!";
            }

            $stmt->close();
        } else {
            $errorMessage = "Error preparing statement: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            background-image: url("{{ url_for('static', filename='images/rural1.jpg')}}");
            background-repeat: no-repeat;
            background-color: #4CAF50;
            background-size: cover;
            height: 70px;
            display: flex;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }

        header img {
            height: 70px;
            margin-right: 33%;
        }

        header h1 {
            font-size: 28px;
            color: white;
            margin: 0;
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
            width: 100%;
            text-align: center;
        }

        h1 {
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

        input, select {
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

        /* Flash messages */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }

        /* Footer */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <img src="{{ url_for('static', filename='images/logo.webp')}}" alt="Logo">
        <h1>FREELANCE JOB PORTAL</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/index.html">Home</a>
        <a href="/about.html">About</a>
        <a href="/services.html">Services</a>
        <a href="/contact.html">Contact</a>
    </nav>

    <main>
        <div class="container">
            <h1>Login</h1>
            <?php if ($errorMessage) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
            <form action="login.php" method="POST">
                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="Client">Client</option>
                    <option value="Freelancer">Freelancer</option>
                </select>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>

</body>
</html>
