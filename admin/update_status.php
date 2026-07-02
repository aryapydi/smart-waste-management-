<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../db/connection.php';

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
header('Content-Type: application/json');
function sendComplaintStatusMail($to, $name, $status, $issue, $remark)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'aryapydi@gmail.com';
        $mail->Password = 'kzhezgwsjzsngvtm';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('aryapydi@gmail.com', 'Smart Waste Management');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Complaint Status Updated';

        $mail->Body = "
        <h2>Hello, $name</h2>

        <p>Your complaint status has been updated.</p>

        <p><b>Issue Type:</b> $issue</p>
        <p><b>Status:</b> $status</p>
        <p><b>Admin Remark:</b> $remark</p>

        <br>

        <p>Thank you for using Smart Waste Management.</p>
        ";

        $mail->send();

    } catch (Exception $e) {
    error_log("Mail Error: " . $mail->ErrorInfo);
}
}
// Must be logged in as admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$allowed_statuses = ['Pending', 'In Progress', 'Resolved'];

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$estimated_days = isset($_POST['estimated_days']) ? intval($_POST['estimated_days']) : 0;
$admin_remark = isset($_POST['admin_remark']) ? trim($_POST['admin_remark']) : '';

if ($id <= 0 || !in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

// Handle resolved image upload
$resolved_image_name = "";
if (isset($_FILES['resolved_image']) && $_FILES['resolved_image']['error'] == 0) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($_FILES['resolved_image']['name'], PATHINFO_EXTENSION));

    if (in_array($file_ext, $allowed_ext)) {
        if ($_FILES['resolved_image']['size'] <= 5 * 1024 * 1024) { // 5MB max
            $resolved_image_name = "resolved_" . time() . "_" . uniqid() . "." . $file_ext;
            $upload_path = "../uploads/" . $resolved_image_name;
            if (!move_uploaded_file($_FILES['resolved_image']['tmp_name'], $upload_path)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Image size must be under 5MB.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG, and GIF images are allowed.']);
        exit();
    }
}

if ($status == "Resolved") {
    if (!empty($resolved_image_name)) {
        $sql = "UPDATE complaints
            SET status = ?,
                estimated_days = ?,
                admin_remark = ?,
                resolved_image = ?,
                completed_at = NOW()
            WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sisisi",
            $status,
            $estimated_days,
            $admin_remark,
            $resolved_image_name,
            $id
        );
    } else {
        $sql = "UPDATE complaints
            SET status = ?,
                estimated_days = ?,
                admin_remark = ?,
                completed_at = NOW()
            WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sisi",
            $status,
            $estimated_days,
            $admin_remark,
            $id
        );
    }
} else {
    $sql = "UPDATE complaints
            SET status = ?,
                estimated_days = ?,
                admin_remark = ?,
                completed_at = NULL
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sisi",
        $status,
        $estimated_days,
        $admin_remark,
        $id
    );
}

if (mysqli_stmt_execute($stmt)) {
    $getUser = mysqli_query($conn, "
SELECT u.name, u.email, c.issue_type
FROM complaints c
JOIN users u ON c.user_id = u.id
WHERE c.id = $id
");

if ($row = mysqli_fetch_assoc($getUser)) {
    sendComplaintStatusMail(
        $row['email'],
        $row['name'],
        $status,
        $row['issue_type'],
        $admin_remark
    );
}
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
mysqli_stmt_close($stmt);
?>
