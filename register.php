<?php
session_start();
include 'db/connection.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$error_msg = "";
function sendMail($to, $name)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPDebug = 3;
        $mail->SMTPAuth = true;

        $mail->Username = 'aryapydi@gmail.com';
        $mail->Password = 'kzhezgwsjzsngvtm'; 

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('aryapydi@gmail.com', 'Smart Waste Management');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Smart Waste Management';

        $mail->Body = "
        <h2>Welcome, $name!</h2>
        <p>Your account has been created successfully.</p>
        <p>You can now login to the Smart Waste Management System.</p>
        <br>
        <b>Thank You!</b>
        ";
        $mail->send();
        return true;

    } catch (Exception $e) {
    die(
        "Mail Error: " . $mail->ErrorInfo .
        "<br><br>Exception: " . $e->getMessage()
    );
}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($name === "" || $email === "" || $phone === "" || $password === "" || $confirm_password === "") {
        $error_msg = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters.";
    } else {
        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error_msg = "An account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $email, $phone, $hashed_password);

            if (mysqli_stmt_execute($insert_stmt)) {

    // Send Welcome Email
    sendMail($email, $name);

    $_SESSION['register_success'] = "Account created successfully! Please log in.";
    header("Location: login.php");
    exit();

} else {
    $error_msg = "Something went wrong. Please try again.";
}
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="auth-wrapper">
    <div class="auth-box">
        <div class="glass-card">
            <h3 class="mb-3 text-center" style="color:var(--accent-color);">Create Account</h3>

            <div id="register_error" class="text-danger mb-2"></div>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger auto-alert"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php" onsubmit="return validateRegister()">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" id="reg_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="reg_email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" id="reg_phone" name="phone" class="form-control" maxlength="10" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="reg_password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" id="reg_confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Register</button>
            </form>

            <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login here</a></p>
            <p class="text-center mt-2 mb-0"><a href="index.php">&larr; Back to Home</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
