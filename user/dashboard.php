<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

$user_id = $_SESSION['user_id'];

// Get stats
$total_sql = "SELECT COUNT(*) as total FROM complaints WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $total_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

$pending_sql = "SELECT COUNT(*) as cnt FROM complaints WHERE user_id = ? AND status = 'Pending'";
$stmt = mysqli_prepare($conn, $pending_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$pending = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cnt'];

$progress_sql = "SELECT COUNT(*) as cnt FROM complaints WHERE user_id = ? AND status = 'In Progress'";
$stmt = mysqli_prepare($conn, $progress_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$progress = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cnt'];

$resolved_sql = "SELECT COUNT(*) as cnt FROM complaints WHERE user_id = ? AND status = 'Resolved'";
$stmt = mysqli_prepare($conn, $resolved_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$resolved = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cnt'];

// Recent complaints
$recent_sql = "SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = mysqli_prepare($conn, $recent_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$recent_result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="dashboard-wrapper">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="brand">🌱 Waste Mgmt</div>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="complaint.php">Raise Complaint</a>
        <a href="complaints.php">My Complaints</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="topbar">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h3>
            <a href="complaint.php" class="btn btn-primary-custom">+ Raise Complaint</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2><?php echo $total; ?></h2>
                    <p>Total Complaints</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2><?php echo $pending; ?></h2>
                    <p>Pending</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2><?php echo $progress; ?></h2>
                    <p>In Progress</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <h2><?php echo $resolved; ?></h2>
                    <p>Resolved</p>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <h5 class="mb-3">Recent Complaints</h5>
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Issue Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Admin Remark</th>
                    <th>Expected Days</th>
                    <th>Raised Date</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($recent_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($recent_result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
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
                            
                            <td>
<?php
echo !empty($row['admin_remark'])
    ? htmlspecialchars($row['admin_remark'])
    : '-';
?>
</td>

<td>
<?php
if($row['status'] == 'In Progress'){
    echo $row['estimated_days'] . ' Days';
}
elseif($row['status'] == 'Resolved'){
    echo 'Completed';
}
else{
    echo '-';
}
?>
</td>
                            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                            <td>
<?php
if (!empty($row['completed_at'])) {
    echo date("d M Y", strtotime($row['completed_at']));
} else {
    echo "-";
}
?>
</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
