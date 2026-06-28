<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

// Issue type breakdown
$type_result = mysqli_query($conn, "SELECT issue_type, COUNT(*) as cnt FROM complaints GROUP BY issue_type ORDER BY cnt DESC");

// Status breakdown
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints"))['cnt'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='Pending'"))['cnt'];
$progress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='In Progress'"))['cnt'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='Resolved'"))['cnt'];

// Most reported locations
$location_result = mysqli_query($conn, "SELECT location, COUNT(*) as cnt FROM complaints GROUP BY location ORDER BY cnt DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="dashboard-wrapper">
    <div class="sidebar">
        <div class="brand">🛠️ Admin Panel</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="complaints.php">Manage Complaints</a>
        <a href="users.php">Manage Users</a>
        <a href="reports.php" class="active">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>Reports &amp; Insights</h3>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="table-wrapper">
                    <h5 class="mb-3">Complaints by Status</h5>
                    <table class="table">
                        <tr><td>Pending</td><td><span class="badge badge-pending"><?php echo $pending; ?></span></td></tr>
                        <tr><td>In Progress</td><td><span class="badge badge-progress"><?php echo $progress; ?></span></td></tr>
                        <tr><td>Resolved</td><td><span class="badge badge-resolved"><?php echo $resolved; ?></span></td></tr>
                        <tr><td><strong>Total</strong></td><td><strong><?php echo $total; ?></strong></td></tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="table-wrapper">
                    <h5 class="mb-3">Complaints by Issue Type</h5>
                    <table class="table">
                        <?php if (mysqli_num_rows($type_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($type_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
                                    <td><?php echo $row['cnt']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="text-muted">No data available.</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-wrapper">
                    <h5 class="mb-3">Top 5 Most Reported Locations</h5>
                    <table class="table">
                        <thead><tr><th>Location</th><th>Number of Complaints</th></tr></thead>
                        <tbody>
                        <?php if (mysqli_num_rows($location_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($location_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td><?php echo $row['cnt']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="text-muted">No data available.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
