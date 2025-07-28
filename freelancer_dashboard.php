<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Freelancer') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <style>
        body, h2, h3, p, ul, li, a {
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
            background: lightgreen;
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
            background: #34495e;
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

        .projects, .messages, .find-projects {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
        }

        .projects ul, .messages ul {
            list-style: none;
            margin-top: 10px;
        }

        .projects ul li, .messages ul li {
            padding: 8px 0;
        }

        .projects ul li a, .messages ul li a {
            color: #3498db;
            text-decoration: none;
        }

        .projects ul li a:hover, .messages ul li a:hover {
            text-decoration: underline;
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
        <aside class="sidebar">
            <h2>Dashboard</h2>
            <nav>
                <ul>
                    <li><a href="freelancer_dashboard.php">🏠 Dashboard</a></li>
                    <li><a href="freelancer_profile.php">👤 My Profile</a></li>
                    <li><a href="view_projects.php">📂 Find Projects</a></li>
                    <li><a href="freelancer_messages.php">💬 Messages</a></li>
                    <li><a href="freelancer_project_work.php">📝 My Projects</a></li>
                    <li><a href="receive_payment.php">💰 Payments</a></li>
                    <li><a href="freelancer_feedback.php">⭐ Feedback</a></li>
                    <li><a href="logout.php" class="logout">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <header>
                <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
            </header>

            <section class="stats">
                <div class="stat-box">Total Earnings: <strong>$XXXX</strong></div>
                <div class="stat-box">Active Projects: <strong>X</strong></div>
                <div class="stat-box">Completed Projects: <strong>X</strong></div>
            </section>
            
            <section class="projects">
                <h3>Ongoing Projects</h3>
                <ul>
                    <li>Project 1 - <a href="freelancer_project_work.php">View Details</a></li>
                    <li>Project 2 - <a href="freelancer_project_work.php">View Details</a></li>
                </ul>
            </section>

            <section class="messages">
                <h3>Recent Messages</h3>
                <ul>
                    <li><a href="freelancer_messages.php">Message from Client XYZ</a></li>
                </ul>
            </section>

            <section class="find-projects">
                <h3>Find New Projects</h3>
                <a href="view_projects.php" class="btn">Browse Projects</a>
            </section>
        </main>
    </div>
</body>
</html>