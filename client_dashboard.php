<?php
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "Client") {
    header("Location: login.php");
    exit();
}

// Fetch Client Name from Session
$client_name = $_SESSION['name'] ?? 'Client';

// Check if client profile exists
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM client_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // If no profile found, redirect to create profile page
    header("Location: client_profile.php");
    exit();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        body, h2, p, ul, li, a {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            background: #f5f5f5;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: darkblue;
            color: white;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }

        .sidebar nav ul li {
            padding: 10px 0;
        }

        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .sidebar nav ul li a:hover {
            background: #1e90ff;
            padding: 5px;
            border-radius: 5px;
        }

        .logout {
            color: #e74c3c;
            font-weight: bold;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        header h2 {
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 15px;
        }

        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
            flex: 1;
            text-align: center;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Client Dashboard</h2>
            <nav>
                <ul>
                    <li><a href="client_dashboard.php">🏠 Dashboard</a></li>
                    <li><a href="client_profile.php">👤 My Profile</a></li>
                    <li><a href="client_jobs.php">📋 My Jobs</a></li>
                    <li><a href="client_messages.php">💬 Messages</a></li>
                    <li><a href="client_payments.php">💰 Payments</a></li>
                    <li><a href="client_feedback.php">⭐ Feedback</a></li>
                    <li><a href="logout.php" class="logout">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <header>
                <h2>Welcome, <?php echo htmlspecialchars($client_name); ?>!</h2>
            </header>

            <section class="stats">
                <div class="stat-box">
                    Posted Jobs: <br>
                    <button onclick="viewDetails('jobs')">View</button>
                </div>
                <div class="stat-box">
                    Reviews: <br>
                    <button onclick="viewDetails('reviews')">View</button>
                </div>
                <div class="stat-box">
                    Saved Freelancers: <br>
                    <button onclick="viewDetails('saved_freelancers')">View</button>
                </div>
            </section>

            <br>
            <center>
                <a href="post_job.php" class="btn">Post A Job</a>
            </center>
        </main>
    </div>

    <script>
        function viewDetails(section) {
            alert('Redirecting to ' + section + ' details.');
        }
    </script>

</body>
</html>
