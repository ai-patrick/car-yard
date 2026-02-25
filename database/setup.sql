-- Prestige Auto Database Setup Script
-- Create Database
CREATE DATABASE IF NOT EXISTS prestige_auto;
USE prestige_auto;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vehicles Table
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description TEXT,
    engine VARCHAR(100),
    power VARCHAR(50),
    acceleration VARCHAR(50),
    drive VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations Table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

-- Payments Table (Paystack)
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reference VARCHAR(100) NOT NULL UNIQUE,
    amount DECIMAL(15, 2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'KES',
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    vehicle_ids JSON NOT NULL,
    paystack_response JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Default Admin User (Password: admin123)
INSERT IGNORE INTO users (username, email, password_hash, role) VALUES 
('admin', 'admin@prestigeauto.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Initial Vehicle Data
INSERT IGNORE INTO vehicles (name, type, price, image_url, description, engine, power, acceleration, drive) VALUES
('Rolls-Royce Phantom Drophead', 'Luxury', 5000.00, 'images/phantom.webp', 'The Phantom Drophead is the pinnacle of open-air luxury motoring.', '6.75L V12 Twin-Turbo', '563 bhp', '5.1 seconds', 'RWD'),
('Porsche 911 Turbo S', 'Sports', 3000.00, 'images/911 turbo.jpg', 'An icon reimagined. The 911 Turbo S distills over five decades of evolution.', '3.8L Flat-6 Twin-Turbo', '650 bhp', '2.7 seconds', 'AWD'),
('Nissan GT-R Nismo', 'Sports', 2500.00, 'images/nippon.jpg', 'Godzilla. Built on the streets of Tokyo and perfected on the track.', '3.8L V6 Twin-Turbo', '600 bhp', '2.5 seconds', 'ATTESA AWD');
