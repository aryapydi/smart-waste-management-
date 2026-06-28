<?php
session_start();
include '../db/connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT status FROM complaints WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $complaint_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(['status' => $row['status']]);
} else {
    echo json_encode(['status' => 'Not Found']);
}
?>
