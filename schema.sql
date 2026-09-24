-- =========================================
-- Pet Adoption Database - Schema
-- =========================================

CREATE DATABASE IF NOT EXISTS pet_adoption;
USE pet_adoption;

-- ---------------------------------
-- Table: users
-- Stores both normal users and admin
-- ---------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    address VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------
-- Table: pets
-- Stores pets available for adoption
-- ---------------------------------
CREATE TABLE IF NOT EXISTS pets (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    species VARCHAR(30) NOT NULL,       -- Dog, Cat, Bird, etc.
    breed VARCHAR(50),
    age INT,
    gender ENUM('Male', 'Female') NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT 'no-image.jpg',
    status ENUM('available', 'pending', 'adopted') DEFAULT 'available',
    added_by INT,                        -- admin user_id who added it
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ---------------------------------
-- Table: adoption_requests
-- Stores adoption requests made by users
-- ---------------------------------
CREATE TABLE IF NOT EXISTS adoption_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ---------------------------------
-- NOTE: The default admin account is NOT created here.
-- Run create_admin.php once (see README) to create it with
-- a properly hashed password. Don't insert plaintext/fake
-- password hashes directly via SQL.
-- ---------------------------------

-- ---------------------------------
-- Sample pets
-- ---------------------------------
INSERT INTO pets (name, species, breed, age, gender, description, status)
VALUES
('Bruno', 'Dog', 'Labrador', 2, 'Male', 'Friendly and energetic, loves kids.', 'available'),
('Whiskers', 'Cat', 'Persian', 1, 'Female', 'Calm and affectionate indoor cat.', 'available'),
('Coco', 'Dog', 'Beagle', 3, 'Female', 'Well trained, great with other pets.', 'available');
