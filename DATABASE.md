foodlynk
    |
    ------- brands
    |
    ------- clients
    |
    ------- meals

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
);

CREATE TABLE clients (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    card_id VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE meals (
    meal_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    brand_name VARCHAR(100) NOT NULL,
    meal_name VARCHAR(255) NOT NULL,
    meal_description VARCHAR(255) NOT NULL,
    meal_price FLOAT NOT NULL,
    meal_quantity INT(11) NOT NULL,
    meal_image BLOB NOT NULL,
    status VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    PRIMARY KEY (meal_id)
);