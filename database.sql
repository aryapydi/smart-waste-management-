-- ===================================================
-- Smart Waste Management and Public Grievance System
-- Database: waste_management_db
-- ===================================================

CREATE DATABASE IF NOT EXISTS waste_management_db;
USE waste_management_db;

-- ---------------------------------------------------
-- USERS TABLE
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- COMPLAINTS TABLE
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS complaints (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    issue_type VARCHAR(50) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255),
    status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------
-- ADMIN TABLE
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- ---------------------------------------------------
-- CONTACT TABLE
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS contact (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- DEFAULT ADMIN LOGIN
-- IMPORTANT: Do NOT insert admin row here with a typed-in hash.
-- Run create_admin.php (included in the project root) ONCE after
-- importing this file. It safely generates a real bcrypt hash using
-- PHP's password_hash() and inserts the admin row for you.
--
-- Default credentials it will create:
-- Username: admin
-- Password: admin123
-- (Change the password after first login in a real deployment.)
-- ---------------------------------------------------
