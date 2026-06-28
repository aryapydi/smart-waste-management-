<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

// Filter by status
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

if ($filter !== 'All') {
    $filter_safe = mysqli_real_escape_string($conn, $filter);
    $sql = "SELECT c.*, u.name as user_name, u.email as user_email FROM complaints c JOIN users u ON c.user_id = u.id WHERE c.status = '$filter_safe' ORDER BY c.created_at DESC";
} else {
    $sql = "SELECT c.*, u.name as user_name, u.email as user_email FROM complaints c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC";
}
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints - Admin</title>
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
        <a href="complaints.php" class="active">Manage Complaints</a>
        <a href="users.php">Manage Users</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>Manage Complaints</h3>
        </div>

        <div class="filter-bar">
            <a href="complaints.php?filter=All" class="btn btn-sm <?php echo $filter=='All'?'btn-primary-custom':'btn-outline-success'; ?>">All</a>
            <a href="complaints.php?filter=Pending" class="btn btn-sm <?php echo $filter=='Pending'?'btn-primary-custom':'btn-outline-success'; ?>">Pending</a>
            <a href="complaints.php?filter=In Progress" class="btn btn-sm <?php echo $filter=='In Progress'?'btn-primary-custom':'btn-outline-success'; ?>">In Progress</a>
            <a href="complaints.php?filter=Resolved" class="btn btn-sm <?php echo $filter=='Resolved'?'btn-primary-custom':'btn-outline-success'; ?>">Resolved</a>
        </div>

        <div id="alert_area"></div>

        <div class="table-wrapper">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Issue Type</th>
                    <th>Map</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Estimated Days</th>
                    <th>Admin Remark</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr id="row_<?php echo $row['id']; ?>">
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?><br><small class="text-muted"><?php echo htmlspecialchars($row['user_email']); ?></small></td>
                            <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
                            <td>
    <a href="https://www.google.com/maps?q=<?php echo urlencode($row['location']); ?>"
       target="_blank"
       class="btn btn-sm btn-success">
       View Map
    </a>
</td>
                            <td style="max-width:220px;"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <?php if ($row['image']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($row['image']); ?>" target="_blank">View</a>
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <select class="form-select form-select-sm status-select" onchange="updateStatus(<?php echo $row['id']; ?>, this.value)">
                                    <option value="Pending" <?php echo $row['status']=='Pending'?'selected':''; ?>>Pending</option>
                                    <option value="In Progress" <?php echo $row['status']=='In Progress'?'selected':''; ?>>In Progress</option>
                                    <option value="Resolved" <?php echo $row['status']=='Resolved'?'selected':''; ?>>Resolved</option>
                                </select>
                            </td>
                            <td>
    <input type="number"
           class="form-control form-control-sm"
           id="days_<?php echo $row['id']; ?>"
           value="<?php echo $row['estimated_days']; ?>"
           min="1"
           style="width:90px;">
</td>
<td>
<textarea
    class="form-control form-control-sm"
    id="remark_<?php echo $row['id']; ?>"
    rows="2"><?php echo htmlspecialchars($row['admin_remark']); ?></textarea>
</td>
                            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center text-muted">No complaints found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function updateStatus(complaintId, newStatus) {
let estimatedDays = document.getElementById('days_' + complaintId).value;
let remark = document.getElementById('remark_' + complaintId).value;
    fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(complaintId) +
      '&status=' + encodeURIComponent(newStatus) +
      '&estimated_days=' + encodeURIComponent(estimatedDays)+
'&admin_remark=' + encodeURIComponent(remark)})
    .then(response => response.json())
    .then(data => {
        const alertArea = document.getElementById('alert_area');
        if (data.success) {
            alertArea.innerHTML = '<div class="alert alert-success auto-alert">Status updated to "' + newStatus + '" for complaint #' + complaintId + '</div>';
        } else {
            alertArea.innerHTML = '<div class="alert alert-danger auto-alert">Failed to update status. Please try again.</div>';
        }
        setTimeout(() => { alertArea.innerHTML = ''; }, 3000);
    })
    .catch(() => {
        document.getElementById('alert_area').innerHTML = '<div class="alert alert-danger">Network error. Please try again.</div>';
    });
}
</script>
</body>
</html>
