<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db/connection.php';

echo "<h3>Database Migration: Adding 'resolved_image' Column...</h3>";

mysqli_report(MYSQLI_REPORT_OFF);

// Add resolved_image
$query = "ALTER TABLE complaints ADD COLUMN resolved_image VARCHAR(255) NULL AFTER admin_remark";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "<p style='color:green;'>Column 'resolved_image' added successfully (or already exists).</p>";
} else {
    echo "<p style='color:orange;'>Column 'resolved_image' failed to add: " . mysqli_error($conn) . "</p>";
}

echo "<p>Migration complete. You can now use before and after images!</p>";
?>
