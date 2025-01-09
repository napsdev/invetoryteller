-- Create the MYSQL 8 database
CREATE DATABASE IF NOT EXISTS cordobaGS;

-- Create the users table
CREATE TABLE IF NOT EXISTS user (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the products table
CREATE TABLE IF NOT EXISTS products (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
purchase_price DECIMAL(10, 2) NOT NULL,
sales_price DECIMAL(10, 2) NOT NULL,
revenue DECIMAL(10, 2) AS (sales_price - purchase_price) STORED,
amount INT NOT NULL
);

-- Create the customers table
CREATE TABLE IF NOT EXISTS customers (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
phone VARCHAR(20) NOT NULL,
address TEXT NOT NULL,
document VARCHAR(50) NOT NULL
);

-- Create the suppliers table
CREATE TABLE IF NOT EXISTS suppliers (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
contact VARCHAR(100),
phone VARCHAR(20),
address TEXT
);

-- Create the payment methods table
CREATE TABLE IF NOT EXISTS paymentmethods (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL,
description TEXT,
value_added int NOT NULL DEFAULT 0,
percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00
);

-- Create the invoices table
CREATE TABLE IF NOT EXISTS invoices (
id INT AUTO_INCREMENT PRIMARY KEY,
customer_id INT NOT NULL,
products JSON NOT NULL,
total INT NOT NULL,
revenue INT NOT NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
pending_call BOOLEAN DEFAULT FALSE,
paymentmethods_id INT DEFAULT NULL,
FOREIGN KEY (customer_id) REFERENCES customers(id),
FOREIGN KEY (paymentmethods_id) REFERENCES paymentmethods(id)
);


-- Create the expenses table
CREATE TABLE IF NOT EXISTS expenses (
id INT AUTO_INCREMENT PRIMARY KEY,
description TEXT NOT NULL,
amount DECIMAL(10, 2) NOT NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the product entries table
CREATE TABLE IF NOT EXISTS productentries (
id INT AUTO_INCREMENT PRIMARY KEY,
product_id INT NOT NULL,
amount INT NOT NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (product_id) REFERENCES products(id)
);


