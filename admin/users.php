<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

$success_msg = "";
$error_msg = "";

// Handle delete user
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        $success_msg = "User deleted successfully.";
    } else {
        $error_msg = "Failed to delete user.";
    }
}

$result = mysqli_query($conn, "SELECT u.*, (SELECT COUNT(*) FROM complaints c WHERE c.user_id = u.id) as complaint_count FROM users u ORDER BY u.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
        <a href="users.php" class="active">Manage Users</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>Manage Users</h3>
        </div>

        <?php if ($success_msg): ?><div class="alert alert-success auto-alert"><?php echo $success_msg; ?></div><?php endif; ?>
        <?php if ($error_msg): ?><div class="alert alert-danger auto-alert"><?php echo $error_msg; ?></div><?php endif; ?>

        <div class="table-wrapper">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Complaints Filed</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo $row['complaint_count']; ?></td>
                            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                            <td class="action-icons">
                                <a href="users.php?delete=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirmAction('Delete this user and all their complaints?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">No registered users yet.</td></tr>
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
