-- =====================================================
-- Medical Inventory Database Schema Update
-- Run this SQL in phpMyAdmin to update your database
-- =====================================================

-- Create Database (if not exists)
CREATE DATABASE IF NOT EXISTS medical_inventory;
USE medical_inventory;

-- =====================================================
-- Drop existing users table to recreate with new columns
-- WARNING: This will delete all existing user data!
-- If you want to keep existing data, use ALTER TABLE instead
-- =====================================================

DROP TABLE IF EXISTS users;

-- Create Users Table with additional fields
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'nurse', 'worker') NOT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_role (role),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Insert Default Admin Account
-- Password: password123 (hashed with BCRYPT)
-- =====================================================

INSERT INTO users (username, password, role, full_name) VALUES 
('admin', '$2y$10$4EH2p9qs/fu4v2WBbLl5/eM/vjmeaqzHu5IKk63FW1xfBfUjonfMy', 'admin', 'System Administrator');

-- =====================================================
-- Insert Sample Nurse and Worker Accounts (Optional)
-- Password for all: password123
-- =====================================================

INSERT INTO users (username, password, role, full_name, email, phone) VALUES 
('nurse1', '$2y$10$4EH2p9qs/fu4v2WBbLl5/eM/vjmeaqzHu5IKk63FW1xfBfUjonfMy', 'nurse', 'Maria Santos', 'maria.santos@hospital.com', '+63 912 345 6789'),
('nurse2', '$2y$10$4EH2p9qs/fu4v2WBbLl5/eM/vjmeaqzHu5IKk63FW1xfBfUjonfMy', 'nurse', 'Anna Cruz', 'anna.cruz@hospital.com', '+63 923 456 7890'),
('worker1', '$2y$10$4EH2p9qs/fu4v2WBbLl5/eM/vjmeaqzHu5IKk63FW1xfBfUjonfMy', 'worker', 'Juan Dela Cruz', 'juan.delacruz@hospital.com', '+63 934 567 8901'),
('worker2', '$2y$10$4EH2p9qs/fu4v2WBbLl5/eM/vjmeaqzHu5IKk63FW1xfBfUjonfMy', 'worker', 'Pedro Garcia', 'pedro.garcia@hospital.com', '+63 945 678 9012');

-- =====================================================
-- Verification Query (Optional - run to check data)
-- =====================================================
-- SELECT * FROM users;
