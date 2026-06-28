<?php
session_start();
include '../db/connection.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $error_msg = "All fields are required.";
    } else {
        $sql = "SELECT id, username, password FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($password == $row['password']) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $row['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error_msg = "Invalid username or password.";
            }
        } else {
            $error_msg = "Invalid username or password.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<section class="auth-wrapper">
    <div class="auth-box">
        <div class="glass-card">
            <h3 class="mb-3 text-center" style="color:var(--accent-color);">Admin Login</h3>

            <?php if ($error_msg): ?>
                <div class="alert alert-danger auto-alert"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0"><a href="../login.php">&larr; User Login</a> &nbsp;|&nbsp; <a href="../index.php">Home</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
