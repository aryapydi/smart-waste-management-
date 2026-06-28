<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));

    if ($name === "" || $phone === "") {
        $error_msg = "Name and phone are required.";
    } else {
        $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $name, $phone, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_name'] = $name;
            $success_msg = "Profile updated successfully!";
        } else {
            $error_msg = "Something went wrong. Please try again.";
        }
    }
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Waste Management</title>
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
        <a href="complaints.php">My Complaints</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>My Profile</h3>
        </div>

        <div class="table-wrapper" style="max-width:600px;">
            <?php if ($success_msg): ?>
                <div class="alert alert-success auto-alert"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger auto-alert"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="profile.php">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (cannot be changed)</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" maxlength="10" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Member Since</label>
                    <input type="text" class="form-control" value="<?php echo date("d M Y", strtotime($user['created_at'])); ?>" disabled>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
