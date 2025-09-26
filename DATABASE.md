The DataBase Structure:

    foodlynk
        |
        ------- brands
        |
        ------- clients
        |
        ------- mealsnew
        |
        -------client_meals

CREATE TABLE brands (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    brand_name VARCHAR(100) NOT NULL,
    brand_image BLOB NOT NULL,
    password VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE clients (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    card_id VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_image BLOB NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE mealsnew (
    meal_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    meal_name VARCHAR(150) NOT NULL,
    meal_description TEXT DEFAULT NULL,
    meal_image VARCHAR(255) DEFAULT NULL,
    meal_quantity INT(11) DEFAULT 0,
    meal_price DECIMAL(10,2) NOT NULL,
    category ENUM('starter','main','drink') DEFAULT 'main',
    is_vegan TINYINT(1) DEFAULT 0,
    is_spicy TINYINT(1) DEFAULT 0,
    is_gluten_free TINYINT(1) DEFAULT 0,
    is_nut_free TINYINT(1) DEFAULT 0,
    is_halal TINYINT(1) DEFAULT 0,
    is_low_carb TINYINT(1) DEFAULT 0,
    is_low_sugar TINYINT(1) DEFAULT 0,
    rating INT(11) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE client_meals (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    client_email VARCHAR(100) NOT NULL,
    brand_name VARCHAR(100) NOT NULL,
    meal_name VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;