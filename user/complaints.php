<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="dashboard-wrapper">
    <div class="sidebar">
        <div class="brand">🌱 Waste Mgmt</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="complaint.php">Raise Complaint</a>
        <a href="complaints.php" class="active">My Complaints</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>My Complaints</h3>
            <a href="complaint.php" class="btn btn-primary-custom">+ Raise Complaint</a>
        </div>

        <div class="table-wrapper">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Issue Type</th>
                    <th>Map</th>
                    <th>Description</th>
                     <th>Before Image</th>
                     <th>After Image</th>
                     <th>Status</th>
                    <th>Expected Days</th>
                    <th>Admin Remark</th>
                    <th>Raised Date</th>
                    <th>Completed Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
                            <td>
    <a href="https://www.google.com/maps?q=<?php echo urlencode($row['location']); ?>"
       target="_blank"
       class="btn btn-sm btn-success">
       View Map
    </a>
</td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                             <td>
                                 <?php if ($row['image']): ?>
                                     <a href="../uploads/<?php echo htmlspecialchars($row['image']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:11px;">View</a>
                                 <?php else: ?>
                                     <span class="text-muted">No Image</span>
                                 <?php endif; ?>
                             </td>
                             <td>
                                 <?php if ($row['resolved_image']): ?>
                                     <a href="../uploads/<?php echo htmlspecialchars($row['resolved_image']); ?>" target="_blank" class="btn btn-sm btn-info text-white py-0 px-2" style="font-size:11px;">View Proof</a>
                                 <?php else: ?>
                                     <span class="text-muted">-</span>
                                 <?php endif; ?>
                             </td>
                            <td>
                                <?php
                                $badge_class = "badge-pending";
                                if ($row['status'] == "In Progress") $badge_class = "badge-progress";
                                if ($row['status'] == "Resolved") $badge_class = "badge-resolved";
                                ?>
                                <span id="status_<?php echo $row['id']; ?>" class="badge <?php echo $badge_class; ?>"><?php echo $row['status']; ?></span>
                            </td>
                            <td>
                                <?php echo !empty($row['estimated_days']) ? htmlspecialchars($row['estimated_days']) . " Days" : '-'; ?>
                            </td>
                            <td>
                                <?php echo !empty($row['admin_remark']) ? htmlspecialchars($row['admin_remark']) : '-'; ?>
                            </td>

<td>
<?php echo date("d M Y", strtotime($row['created_at'])); ?>
</td>

<td>
<?php
if(!empty($row['completed_at'])){
    echo date("d M Y", strtotime($row['completed_at']));
}
else{
    echo "-";
}
?>
</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="11" class="text-center text-muted">No complaints raised yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/app.js"></script>
</body>
</html>
