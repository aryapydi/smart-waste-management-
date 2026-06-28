<?php
session_start();
include '../db/connection.php';
include 'auth_check.php';

$error_msg = "";
$success_msg = "";
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = "";

// Fetch user email for EmailJS notifications
$stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $user_email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$issue_type = "";
$location = "";
$description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_type = mysqli_real_escape_string($conn, trim($_POST['issue_type']));
    $location = mysqli_real_escape_string($conn, trim($_POST['location']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $image_name = "";

    if ($issue_type === "" || $location === "" || $description === "") {
        $error_msg = "Please fill all required fields.";
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // 5MB max
                    $image_name = "complaint_" . time() . "_" . uniqid() . "." . $file_ext;
                    $upload_path = "../uploads/" . $image_name;
                    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
                } else {
                    $error_msg = "Image size must be under 5MB.";
                }
            } else {
                $error_msg = "Only JPG, JPEG, PNG, and GIF images are allowed.";
            }
        }

        if ($error_msg === "") {
            $sql = "INSERT INTO complaints (user_id, issue_type, location, description, image, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "issss", $user_id, $issue_type, $location, $description, $image_name);

            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Complaint submitted successfully! You can track its status in 'My Complaints'.";
            } else {
                $error_msg = "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise Complaint - Smart Waste Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>

<div class="dashboard-wrapper">
    <div class="sidebar">
        <div class="brand">🌱 Waste Mgmt</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="complaint.php" class="active">Raise Complaint</a>
        <a href="complaints.php">My Complaints</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h3>Raise a Complaint</h3>
        </div>

        <div class="table-wrapper" style="max-width:700px;">
            <div id="complaint_error" class="text-danger mb-2"></div>
            <?php if ($success_msg): ?>
                <div class="alert alert-success auto-alert"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger auto-alert"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="complaint.php" enctype="multipart/form-data" onsubmit="return validateComplaint()">
                <div class="mb-3">
                    <label class="form-label">Issue Type</label>
                    <select id="issue_type" name="issue_type" class="form-select" required>
                        <option value="">-- Select Issue Type --</option>
                        <option value="Garbage">Garbage</option>
                        <option value="Drainage">Drainage</option>
                        <option value="Mosquito">Mosquito</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <div class="input-group mb-2">
                        <input type="text" id="location" name="location" class="form-control" placeholder="Street, area, or landmark" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="getCurrentLocation()">📍 Use Current</button>
                    </div>
                    <!-- Map Container -->
                    <div id="map" style="height: 200px; width: 100%; border-radius: 8px; margin-bottom: 10px;"></div>
                    <small class="text-muted">You can click on the map or drag the marker to select a specific location.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe the issue in detail" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Photo (optional)</label>
                    <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(event)">
                    <img id="image_preview" class="img-preview" alt="Preview">
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Submit Complaint</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/app.js"></script>

<!-- Google Maps API integration -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
var map = L.map('map').setView([18.069755, 83.507656], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var marker = L.marker([18.069755, 83.507656], {
    draggable: true
}).addTo(map);

marker.on('dragend', function(e) {
    var pos = marker.getLatLng();
    document.getElementById('location').value =
        pos.lat.toFixed(6) + ", " + pos.lng.toFixed(6);
});

map.on('click', function(e) {
    marker.setLatLng(e.latlng);
    document.getElementById('location').value =
        e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6);
});

function getCurrentLocation() {
    navigator.geolocation.getCurrentPosition(function(position) {

        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        map.setView([lat, lng], 16);
        marker.setLatLng([lat, lng]);

        document.getElementById('location').value =
            lat.toFixed(6) + ", " + lng.toFixed(6);

    }, function() {
        alert("Location access denied");
    });
}
</script>
<!-- REPLACE 'YOUR_GOOGLE_MAPS_API_KEY' WITH YOUR ACTUAL KEY -->

<!-- EmailJS integration -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
<script type="text/javascript">
    (function() {
        // REPLACE 'YOUR_EMAILJS_PUBLIC_KEY' WITH YOUR ACTUAL PUBLIC KEY
        emailjs.init("YOUR_EMAILJS_PUBLIC_KEY");
    })();
</script>

<?php if ($success_msg): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var templateParams = {
            user_name: "<?php echo addslashes($user_name); ?>",
            user_email: "<?php echo addslashes($user_email); ?>",
            issue_type: "<?php echo addslashes($issue_type); ?>",
            location: "<?php echo addslashes($location); ?>",
            description: "<?php echo addslashes($description); ?>"
        };

        // REPLACE 'YOUR_SERVICE_ID' AND 'YOUR_TEMPLATE_ID' WITH YOUR ACTUAL IDs
        emailjs.send("YOUR_SERVICE_ID", "YOUR_TEMPLATE_ID", templateParams)
            .then(function(response) {
                console.log("Email sent successfully!", response.status, response.text);
            }, function(error) {
                console.error("Failed to send email.", error);
            });
    });
</script>
<?php endif; ?>

</body>
</html>
