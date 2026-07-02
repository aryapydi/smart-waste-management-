<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_host = "sql209.infinityfree.com";
$db_user = "if0_42288895";
$db_pass = "RJsFgNtiudP"; 
$db_name = "if0_42288895_smartwaste";

echo "<h3>Testing Database Connection...</h3>";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    echo "<p style='color:red;'>Connection Failed: " . mysqli_connect_error() . "</p>";
} else {
    echo "<p style='color:green;'>Connection Successful! Database is working perfectly.</p>";
    mysqli_close($conn);
}
?>
