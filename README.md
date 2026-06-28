# Smart Waste Management and Public Grievance System

A full-stack web application built with **HTML5, CSS3, JavaScript, Bootstrap 5, PHP 8, and MySQL**, allowing citizens to report waste/sanitation issues and track complaint status, with a separate admin panel to manage and resolve them.

---

## 🚀 Setup Instructions (XAMPP)

### 1. Copy the project folder
Copy the entire `smart-waste-management` folder into your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\smart-waste-management   (Windows)
/Applications/XAMPP/htdocs/smart-waste-management   (Mac)
```

### 2. Start Apache and MySQL
Open the XAMPP Control Panel and start both **Apache** and **MySQL**.

### 3. Create the database
1. Open `http://localhost/phpmyadmin`
2. Click **Import**
3. Choose the file `database.sql` from the project folder
4. Click **Go** — this creates the `waste_management_db` database with all 4 tables (`users`, `complaints`, `admin`, `contact`)

### 4. Create the default admin account
In your browser, visit:
```
http://localhost/smart-waste-management/create_admin.php
```
This safely generates a secure password hash and creates the admin login:
- **Username:** `admin`
- **Password:** `admin123`

⚠️ **After it says "Admin created successfully", delete `create_admin.php`** from the project folder — it's a one-time setup script and shouldn't stay on a live server.

### 5. Open the project
```
http://localhost/smart-waste-management/index.php
```

---

## 🔑 Login Details

| Role  | URL | Username/Email | Password |
|-------|-----|------|----------|
| User  | `login.php` | (register your own) | (set at registration) |
| Admin | `admin/login.php` | `admin` | `admin123` |

**Change the admin password after first login in any real deployment.**

---

## 📁 Folder Structure
```
smart-waste-management/
├── index.php, about.php, contact.php, register.php, login.php
├── create_admin.php        (run once, then delete)
├── database.sql            (import this first)
├── user/                   (dashboard, complaint form, my complaints, profile)
├── admin/                  (dashboard, manage complaints, manage users, reports)
├── css/                    (style.css, dashboard.css, admin.css)
├── js/                     (app.js)
├── uploads/                (complaint images, .htaccess blocks script execution here)
└── db/connection.php
```

---

## ✅ Features Implemented
- User registration & login (passwords hashed with `password_hash()`, never stored in plain text)
- Admin login (separate session, separate table)
- Complaint submission with image upload (validated file type & size)
- Complaint status tracking (Pending → In Progress → Resolved) with live AJAX updates
- Admin dashboard with stats, filters, and one-click status updates
- User management (view + delete) for admins
- Reports page: status breakdown, issue-type breakdown, top reported locations
- Contact form (saved to database)
- All SQL queries use **prepared statements** (SQL-injection safe)
- Mobile-responsive, glassmorphism-styled UI with Bootstrap 5

---

## 🔮 Optional Future Additions (not included, as agreed)
- Google Maps API for pinning complaint location
- EmailJS for status-update email notifications
- AI-based automatic complaint classification
- AI chatbot for FAQs

These can be added later without changing the existing structure.
