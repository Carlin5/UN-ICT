-- Create database
CREATE DATABASE IF NOT EXISTS un_ict_db;
USE un_ict_db;

-- Create messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date DATETIME NOT NULL,
    read_status BOOLEAN DEFAULT FALSE
); 