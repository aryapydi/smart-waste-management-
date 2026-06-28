<?php
// Include this at the top of every protected USER page (after session_start())
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
