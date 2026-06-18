-- TourStay Hotel Management System Database
-- Database: hotel

CREATE DATABASE IF NOT EXISTS hotel;
USE hotel;

-- 1. Users Table
CREATE TABLE users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Hotels Table
CREATE TABLE hotels (
    hotel_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_name VARCHAR(255) NOT NULL,
    h_username VARCHAR(30) NOT NULL,
    h_password VARCHAR(40) NOT NULL,
    hotel_description TEXT,
    hotel_address TEXT,
    hotel_phone VARCHAR(20),
    hotel_email VARCHAR(100),
    hotel_image VARCHAR(255),
    hotel_rating DECIMAL(2,1) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Rooms Table
CREATE TABLE rooms (
    r_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT,
    rtype VARCHAR(100) NOT NULL,
    rprice DECIMAL(10,2) NOT NULL,
    rtext TEXT,
    rimage VARCHAR(255),
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id) ON DELETE SET NULL
);

-- 4. Booked Table
CREATE TABLE booked (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    ID INT NOT NULL,
    r_id INT NOT NULL,
    hotel_id INT,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT DEFAULT 1,
    price_per_night DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
   
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID) REFERENCES users(ID) ON DELETE CASCADE,
    FOREIGN KEY (r_id) REFERENCES rooms(r_id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id) ON DELETE SET NULL
);

-- 5. Detail Table
CREATE TABLE detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -- 6. Contact Table
-- CREATE TABLE contact (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     email VARCHAR(100) NOT NULL,
--     subject VARCHAR(200) NOT NULL,
--     message TEXT NOT NULL,
--     approval ENUM('Allowed', 'Not Allowed') DEFAULT 'Not Allowed',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- 7. System Settings Table
-- CREATE TABLE system_settings (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     hotel_name VARCHAR(255) NOT NULL DEFAULT 'TourStay',
--     email VARCHAR(100),
--     contact VARCHAR(20),
--     about_content TEXT,
--     cover_img VARCHAR(255),
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- 8. Checked Table
-- CREATE TABLE checked (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     room_id INT NOT NULL,
--     name VARCHAR(100) NOT NULL,
--     contact_no VARCHAR(20) NOT NULL,
--     date_in DATETIME NOT NULL,
--     date_out DATETIME NOT NULL,
--     ref_no VARCHAR(20) UNIQUE NOT NULL,
--     status ENUM('checked_in', 'checked_out') DEFAULT 'checked_in',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (room_id) REFERENCES rooms(r_id) ON DELETE CASCADE
-- );

-- -- 9. Room Categories Table
-- CREATE TABLE room_categories (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     price DECIMAL(10,2) NOT NULL,
--     cover_img VARCHAR(255),
--     description TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Sample Data
INSERT INTO hotels (hotel_name, h_username, h_password, hotel_description, hotel_address, hotel_phone, hotel_email, hotel_image) VALUES
('TourStay Himalayan Kathmandu', 'kathmandu_admin', 'ktm123', 'Luxury hotel in Kathmandu with mountain views', 'Thamel, Kathmandu 44600, Nepal', '+977-1-4441234', 'kathmandu@tourstay.com', 'images/resources/bg1.jpg'),
('TourStay Lake Pokhara', 'pokhara_admin', 'pkr123', 'Lakeside resort in Pokhara with Annapurna views', 'Lakeside, Pokhara 33700, Nepal', '+977-61-465432', 'pokhara@tourstay.com', 'images/resources/bg3.jpg'),
('TourStay Heritage Bhaktapur', 'bhaktapur_admin', 'bkt123', 'Heritage hotel in ancient Bhaktapur city', 'Durbar Square, Bhaktapur 44800, Nepal', '+977-1-6610987', 'bhaktapur@tourstay.com', 'images/resources/bg4.jpg');

-- INSERT INTO room_categories (name, price, description) VALUES
-- ('Standard Room', 1200.00, 'Comfortable standard room with basic amenities'),
-- ('Deluxe Room', 1800.00, 'Spacious deluxe room with premium amenities'),
-- ('Suite', 2500.00, 'Luxury suite with separate living area'),
-- ('Presidential Suite', 4000.00, 'Ultimate luxury with panoramic views');

INSERT INTO rooms (hotel_id, rtype, rprice, rtext, rimage) VALUES
(1, 'Single Room', 1200.00, 'Comfortable single room with city view', '59ccf7c8c79d4.jpg'),
(1, 'Double Room', 1800.00, 'Spacious double room with ocean view', '59ccf7a2ac7f3.jpg'),
(1, 'First Class', 2500.00, 'Luxury first class room with separate living area', '59ccf6f67339e.jpg'),
(2, 'Single Room', 1100.00, 'Beachfront single room', '59ccf692108b5.jpg'),
(2, 'Double Room', 1700.00, 'Double room with beach access', '59ccf6668755a.jpg'),
(2, 'First Class', 2400.00, 'First class room with private balcony', '59ccf62887933.jpg'),
(3, 'Single Room', 1300.00, 'Business single room', '59ccf8703ad11.jpg'),
(3, 'Double Room', 1900.00, 'Executive double room', '59ccf895acf8f.jpg'),
(3, 'First Class', 2600.00, 'First class room with conference area', '59ccf8ade0a79.jpg'),
(3, 'First Class', 4000.00, 'Premium first class room with city skyline view', '59ccf84ccda87.jpg');

INSERT INTO users (Name, Email, Phone, Password) VALUES
('John Doe', 'john@example.com', '0123456789', 'password123'),
('Jane Smith', 'jane@example.com', '0987654321', 'password456'),
('Mike Johnson', 'mike@example.com', '0555123456', 'password789');

INSERT INTO system_settings (hotel_name, email, contact, about_content) VALUES
('TourStay Hotel Management', 'info@tourstay.com', '+977-1-4441234', 'Welcome to TourStay - Your premier hotel booking destination in Nepal.');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(Email);
CREATE INDEX idx_rooms_hotel ON rooms(hotel_id);
CREATE INDEX idx_booked_user ON booked(ID);
CREATE INDEX idx_booked_room ON booked(r_id);
CREATE INDEX idx_booked_dates ON booked(check_in, check_out);
CREATE INDEX idx_hotels_status ON hotels(status);
CREATE INDEX idx_contact_created ON contact(created_at);