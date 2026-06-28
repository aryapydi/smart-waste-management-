<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
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

<!-- HERO -->
<section class="hero">
    <h1>Smart Waste Management &amp; Public Grievance System</h1>
    <p>Report waste, drainage, and sanitation issues in your area. Track complaint status in real-time and help build a cleaner community.</p>
    <a href="<?php echo isset($_SESSION['user_id']) ? 'user/complaint.php' : 'login.php'; ?>" class="btn btn-light btn-primary-custom fw-bold">Raise a Complaint</a>
</section>

<!-- AWARENESS SECTION -->
<section class="section">
    <h2 class="section-title">Why It Matters</h2>
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="glass-card">
                    <h4>🗑️ Cleaner Streets</h4>
                    <p>Report uncollected garbage and overflowing bins quickly so authorities can act faster.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h4>💧 Drainage Issues</h4>
                    <p>Flag blocked drains and water-logging spots before they become health hazards.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h4>🦟 Mosquito Control</h4>
                    <p>Highlight stagnant water and breeding spots to help reduce disease outbreaks.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" style="background:#e8f5e9;">
    <h2 class="section-title">How It Works</h2>
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <h5>1. Register</h5>
                <p>Create your free account in seconds.</p>
            </div>
            <div class="col-md-3">
                <h5>2. Raise Complaint</h5>
                <p>Describe the issue and add a photo.</p>
            </div>
            <div class="col-md-3">
                <h5>3. Track Status</h5>
                <p>Follow progress from Pending to Resolved.</p>
            </div>
            <div class="col-md-3">
                <h5>4. Get Resolved</h5>
                <p>Admins act and update the status.</p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> Smart Waste Management System. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
