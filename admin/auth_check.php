<?php
// Include this at the top of every protected ADMIN page (after session_start())
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
