CREATE DATABASE car_showroom;

USE car_showroom;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO cars (category_id, name, image_path, description) VALUES
(6, 'BMW 2 GRAN COUPE', 'images/BMW 2 GRAN COUPE.png', 'A luxurious compact Gran Coupe.'),
(6, 'BMW 2 Series', 'images/BMW 2 Series.png', 'The versatile BMW 2 Series.'),
(7, 'BMW 3 GRAN LIMOUSINE', 'images/BMW 3 GRAN LIMOUSINE.png', 'The extended luxury of the BMW 3 Series.'),
(5, 'BMW 5 LONG WHEELBASE', 'images/BMW 5 LONG WHEELBASE.png', 'An extended version of the BMW 5 Series.'),
(4, 'BMW 7 PROTECTION', 'images/BMW 7 PROTECTION.png', 'An armored luxury sedan.'),
(4, 'BMW 7 SEDAN', 'images/BMW 7 SEDAN.png', 'The iconic BMW 7 Series Sedan.'),
(7, 'BMW 320', 'images/BMW 320.png', 'The classic BMW 320.'),
(7, 'BMW 340i', 'images/BMW 340i.png', 'A high-performance variant of the 3 Series.'),
(1, 'BMW i5', 'images/BMW i5.png', 'An innovative electric sedan.'),
(1, 'BMW i7', 'images/BMW i7.png', 'The luxury electric BMW i7.'),
(2, 'BMW iX', 'images/BMW iX.png', 'The future of SUVs with BMW iX.'),
(2, 'BMW ix1', 'images/BMW ix1.png', 'A compact electric SUV.'),
(3, 'BMW M2', 'images/BMW M2.png', 'A high-performance sports car.'),
(3, 'BMW M8', 'images/BMW M8.png', 'The ultimate BMW M sports car.'),
(3, 'BMW M40i', 'images/BMW M40i.png', 'An M performance luxury car.'),
(2, 'BMW X1', 'images/BMW X1.png', 'A versatile compact SUV.'),
(2, 'BMW X3', 'images/BMW X3.png', 'A luxurious and powerful SUV.'),
(2, 'BMW X5', 'images/BMW X5.png', 'The perfect balance of luxury and performance.'),
(2, 'BMW X7', 'images/BMW X7.png', 'A large and luxurious BMW SUV.'),
(8, 'BMW Z4 M40i', 'images/BMW Z4 M40i.png', 'A stylish and dynamic roadster.');




CREATE TABLE car_information (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Unique ID for each car
    name VARCHAR(255) NOT NULL,                -- Car name (e.g., MERCEDES BENZ CLA 200 SPORTS)
    price DECIMAL(10, 2) NOT NULL,             -- Car price (e.g., 2750000.00)
    image VARCHAR(255) NOT NULL,               -- Image path or URL
    manufacturing_year YEAR NOT NULL,          -- Manufacturing year (e.g., 2019)
    kilometers_done VARCHAR(50) NOT NULL,      -- Kilometers driven (e.g., 22000 KM)
    transmission VARCHAR(50) NOT NULL,         -- Transmission type (e.g., Automatic)
    fuel VARCHAR(50) NOT NULL,                 -- Fuel type (e.g., Petrol)
    registration_year YEAR NOT NULL,           -- Registration year (e.g., 2019)
    exterior_color VARCHAR(50) NOT NULL,       -- Car color (e.g., White)
    ground_clearance VARCHAR(50) NOT NULL,     -- Ground clearance (e.g., 160mm)
    boot_space VARCHAR(50) NOT NULL,           -- Boot space (e.g., 470ltr)
    torque VARCHAR(50) NOT NULL,               -- Torque (e.g., 300NM)
    variant VARCHAR(255) NOT NULL,             -- Car variant (e.g., CLA 200 Sports)
    power VARCHAR(50) NOT NULL,                -- Engine power (e.g., 181 BHP)
    engine_capacity VARCHAR(50) NOT NULL,      -- Engine capacity (e.g., 2.0 LTRs)
    ownership_status VARCHAR(50) NOT NULL,     -- Ownership status (e.g., First)
    registered_state VARCHAR(50) NOT NULL,     -- State of registration (e.g., MH)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp for record creation
);
