/* ===================================================
   Smart Waste Management - app.js
   =================================================== */

// -------------------- LOGIN VALIDATION --------------------
function validateLogin() {
    const email = document.getElementById("login_email").value.trim();
    const password = document.getElementById("login_password").value.trim();
    const errorBox = document.getElementById("login_error");
    errorBox.innerHTML = "";

    if (email === "" || password === "") {
        errorBox.innerHTML = "All fields are required.";
        return false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errorBox.innerHTML = "Please enter a valid email address.";
        return false;
    }

    if (password.length < 6) {
        errorBox.innerHTML = "Password must be at least 6 characters.";
        return false;
    }

    return true;
}

// -------------------- REGISTRATION VALIDATION --------------------
function validateRegister() {
    const name = document.getElementById("reg_name").value.trim();
    const email = document.getElementById("reg_email").value.trim();
    const phone = document.getElementById("reg_phone").value.trim();
    const password = document.getElementById("reg_password").value.trim();
    const confirmPassword = document.getElementById("reg_confirm_password").value.trim();
    const errorBox = document.getElementById("register_error");
    errorBox.innerHTML = "";

    if (name === "" || email === "" || phone === "" || password === "" || confirmPassword === "") {
        errorBox.innerHTML = "All fields are required.";
        return false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errorBox.innerHTML = "Please enter a valid email address.";
        return false;
    }

    if (!/^[0-9]{10}$/.test(phone)) {
        errorBox.innerHTML = "Phone number must be exactly 10 digits.";
        return false;
    }

    if (password.length < 6) {
        errorBox.innerHTML = "Password must be at least 6 characters.";
        return false;
    }

    if (password !== confirmPassword) {
        errorBox.innerHTML = "Passwords do not match.";
        return false;
    }

    return true;
}

// -------------------- COMPLAINT FORM VALIDATION --------------------
function validateComplaint() {
    const issueType = document.getElementById("issue_type").value;
    const location = document.getElementById("location").value.trim();
    const description = document.getElementById("description").value.trim();
    const errorBox = document.getElementById("complaint_error");
    errorBox.innerHTML = "";

    if (issueType === "" || location === "" || description === "") {
        errorBox.innerHTML = "Please fill all required fields.";
        return false;
    }

    if (description.length < 10) {
        errorBox.innerHTML = "Description should be at least 10 characters.";
        return false;
    }

    return true;
}

// -------------------- IMAGE PREVIEW --------------------
function previewImage(event) {
    const preview = document.getElementById("image_preview");
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = "none";
    }
}

// -------------------- FETCH COMPLAINT STATUS (AJAX) --------------------
function fetchComplaintStatus(complaintId) {
    fetch("user/check_status.php?id=" + complaintId)
        .then(response => response.json())
        .then(data => {
            const statusEl = document.getElementById("status_" + complaintId);
            if (statusEl) {
                statusEl.innerText = data.status;
            }
        })
        .catch(error => console.error("Error fetching status:", error));
}

// -------------------- AUTO-HIDE ALERTS --------------------
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".auto-alert");
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.display = "none";
        }, 4000);
    });
});

// -------------------- CONFIRM DELETE / STATUS CHANGE --------------------
function confirmAction(message) {
    return confirm(message || "Are you sure you want to proceed?");
}
