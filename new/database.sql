-- Create database
CREATE DATABASE IF NOT EXISTS nutquna_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nutquna_db;

-- Users table (for all user types)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    user_type ENUM('parent', 'specialist', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Parents specific information
CREATE TABLE IF NOT EXISTS parents (
    parent_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    child_name VARCHAR(255) NOT NULL,
    child_age INT NOT NULL,
    autism_level ENUM('low', 'medium', 'high') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Specialists specific information
CREATE TABLE IF NOT EXISTS specialists (
    specialist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    specialty ENUM('psychologist', 'speech_therapist', 'occupational_therapist', 'behavioral_therapist') NOT NULL,
    license_number VARCHAR(100) NOT NULL,
    is_verified BOOLEAN DEFAULT FALSE,
    phone VARCHAR(20),
    address TEXT,
    qualification TEXT,
    experience INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS children (
    child_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    autism_level ENUM('low', 'medium', 'high') NOT NULL,
    join_date DATE NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES users(user_id) ON DELETE CASCADE
);


-- Create admin user
INSERT INTO users (email, password, full_name, user_type) 
VALUES ('admin@nutquna.com', '$2y$10$IlWzPzQGLzKTwCuLTiOKq.CJ/QQp3nOGpFLX.StnEjoNMKrBMi2g.', 'مدير النظام', 'admin');
-- Password is 'admin123' hashed with password_hash()