CREATE DATABASE computer_inventory;

USE computer_inventory;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    permissions JSON DEFAULT ('{"view": true, "edit": false, "add": false}'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE computers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('Laptop', 'Desktop') NOT NULL,
    serial_number VARCHAR(255) UNIQUE NOT NULL,
    brand VARCHAR(255) NOT NULL,
    ip_address VARCHAR(255) NOT NULL,
    mac_address VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    existing_user VARCHAR(255) NOT NULL,
    other_details VARCHAR(255) NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
