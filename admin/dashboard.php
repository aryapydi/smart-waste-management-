<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

// Stats
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints"))['cnt'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='Pending'"))['cnt'];
$progress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='In Progress'"))['cnt'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='Resolved'"))['cnt'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users"))['cnt'];

// Recent complaints
$recent_result = mysqli_query($conn, "SELECT c.*, u.name as user_name FROM complaints c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="dashboard-wrapper">
    <div class="sidebar">
        <div class="brand">🛠️ Admin Panel</div>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="complaints.php">Manage Complaints</a>
        <a href="users.php">Manage Users</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 🛠️</h3>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-2 col-6">
                <div class="stat-card"><h2><?php echo $total; ?></h2><p>Total Complaints</p></div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stat-card"><h2><?php echo $pending; ?></h2><p>Pending</p></div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stat-card"><h2><?php echo $progress; ?></h2><p>In Progress</p></div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stat-card"><h2><?php echo $resolved; ?></h2><p>Resolved</p></div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stat-card"><h2><?php echo $total_users; ?></h2><p>Total Users</p></div>
            </div>
        </div>

        <div class="table-wrapper">
            <h5 class="mb-3">Recent Complaints</h5>
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Issue Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($recent_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($recent_result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td>
                                <?php
                                $badge_class = "badge-pending";
                                if ($row['status'] == "In Progress") $badge_class = "badge-progress";
                                if ($row['status'] == "Resolved") $badge_class = "badge-resolved";
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo $row['status']; ?></span>
                            </td>
                            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">No complaints yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <a href="complaints.php" class="btn btn-primary-custom mt-2">View All Complaints</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
