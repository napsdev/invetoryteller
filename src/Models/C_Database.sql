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
nombre VARCHAR(255) NOT NULL,
precio_compra DECIMAL(10, 2) NOT NULL,
precio_venta DECIMAL(10, 2) NOT NULL,
ganancia DECIMAL(10, 2) AS (precio_venta - precio_compra) STORED,
cantidad INT NOT NULL
);

-- Create the customers table
CREATE TABLE IF NOT EXISTS customers (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(255) NOT NULL,
celular VARCHAR(20) NOT NULL,
direccion TEXT NOT NULL,
llamada_pendiente BOOLEAN DEFAULT FALSE,
documento VARCHAR(50) NOT NULL
);

-- Create the suppliers table
CREATE TABLE IF NOT EXISTS suppliers (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(255) NOT NULL,
contacto VARCHAR(100),
telefono VARCHAR(20),
direccion TEXT
);

-- Create the payment methods table
CREATE TABLE IF NOT EXISTS paymentmethods (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(50) NOT NULL,
descripcion TEXT,
value_added int NOT NULL DEFAULT 0,
percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00
);

-- Create the invoices table
CREATE TABLE IF NOT EXISTS invoices (
id INT AUTO_INCREMENT PRIMARY KEY,
customer_id INT NOT NULL,
productos JSON NOT NULL,
total INT NOT NULL,
ganancia INT NOT NULL,
fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
paymentmethods_id INT DEFAULT NULL,
FOREIGN KEY (customer_id) REFERENCES customers(id),
FOREIGN KEY (paymentmethods_id) REFERENCES paymentmethods(id)
);


-- Create the expenses table
CREATE TABLE IF NOT EXISTS expenses (
id INT AUTO_INCREMENT PRIMARY KEY,
descripcion TEXT NOT NULL,
monto DECIMAL(10, 2) NOT NULL,
fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the product entries table
CREATE TABLE IF NOT EXISTS productentries (
id INT AUTO_INCREMENT PRIMARY KEY,
product_id INT NOT NULL,
cantidad INT NOT NULL,
fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (product_id) REFERENCES products(id)
);


