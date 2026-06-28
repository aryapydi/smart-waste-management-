<?php
session_start();
include 'db/connection.php';

$error_msg = "";
$success_msg = isset($_SESSION['register_success']) ? $_SESSION['register_success'] : "";
unset($_SESSION['register_success']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        $error_msg = "All fields are required.";
    } else {
        $sql = "SELECT id, name, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                header("Location: user/dashboard.php");
                exit();
            } else {
                $error_msg = "Invalid email or password.";
            }
        } else {
            $error_msg = "Invalid email or password.";
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
    <title>Login - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="auth-wrapper">
    <div class="auth-box">
        <div class="glass-card">
            <h3 class="mb-3 text-center" style="color:var(--accent-color);">User Login</h3>

            <div id="login_error" class="text-danger mb-2"></div>
            <?php if ($success_msg): ?>
                <div class="alert alert-success auto-alert"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger auto-alert"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php" onsubmit="return validateLogin()">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="login_email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="login_password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0">Don't have an account? <a href="register.php">Register here</a></p>
            <p class="text-center mt-2 mb-0"><a href="admin/login.php">Admin Login</a> &nbsp;|&nbsp; <a href="index.php">Back to Home</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
