<?php
// Database connection settings
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "waste_management_db";

// Turn off mysqli exception throwing to show readable error instead of HTTP 500
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
