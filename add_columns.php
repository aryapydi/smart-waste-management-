<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db/connection.php';

echo "<h3>Adding Missing Columns to Database...</h3>";

// Disable exception throwing temporarily for smooth query runs
mysqli_report(MYSQLI_REPORT_OFF);

// Add estimated_days
$q1 = mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN estimated_days INT(11) DEFAULT 0 AFTER status");
if ($q1) {
    echo "<p style='color:green;'>Column 'estimated_days' added successfully (or already exists).</p>";
} else {
    echo "<p style='color:orange;'>Column 'estimated_days' failed to add: " . mysqli_error($conn) . "</p>";
}

// Add admin_remark
$q2 = mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN admin_remark TEXT AFTER estimated_days");
if ($q2) {
    echo "<p style='color:green;'>Column 'admin_remark' added successfully (or already exists).</p>";
} else {
    echo "<p style='color:orange;'>Column 'admin_remark' failed to add: " . mysqli_error($conn) . "</p>";
}

// Add completed_at
$q3 = mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN completed_at TIMESTAMP NULL DEFAULT NULL AFTER admin_remark");
if ($q3) {
    echo "<p style='color:green;'>Column 'completed_at' added successfully (or already exists).</p>";
} else {
    echo "<p style='color:orange;'>Column 'completed_at' failed to add: " . mysqli_error($conn) . "</p>";
}

echo "<p>All checks completed. Please try updating the complaint status again now!</p>";
?>
