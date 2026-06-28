<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">🌱 Smart Waste Mgmt</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="user/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="user/logout.php">Logout</a></li>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="admin/dashboard.php">Admin Panel</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<section class="section">
    <div class="container">
        <div class="glass-card">
            <h2 class="mb-3" style="color:var(--accent-color);">About This Project</h2>
            <p>The <strong>Smart Waste Management and Public Grievance System</strong> is a web platform that allows citizens to report waste, drainage, and sanitation issues directly to local authorities.</p>
            <p>Users can register, raise complaints with a description, location, and photo, and track the status of their complaint — from <strong>Pending</strong> to <strong>In Progress</strong> to <strong>Resolved</strong>.</p>
            <p>Administrators can log in to a dedicated dashboard to review all complaints, update their status, and manage registered users — making civic issue resolution faster and more transparent.</p>
            <h5 class="mt-4" style="color:var(--accent-color);">Key Features</h5>
            <ul>
                <li>User registration and secure login</li>
                <li>Complaint submission with image upload</li>
                <li>Real-time complaint status tracking</li>
                <li>Admin dashboard for complaint and user management</li>
                <li>Mobile-responsive design</li>
            </ul>
        </div>
    </div>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Smart Waste Management System. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
