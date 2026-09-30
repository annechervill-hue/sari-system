CREATE DATABASE IF NOT EXISTS sari_store_db;
USE sari_store_db;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    reorder_level INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATETIME NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    receipt_no VARCHAR(50) NOT NULL
);

CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id)
);

INSERT INTO products (name, category, quantity, price, reorder_level)
VALUES
('Rice 5kg', 'Groceries', 25, 220.00, 8),
('Coke 1L', 'Beverage', 15, 65.00, 5),
('Instant Noodles', 'Snacks', 35, 18.00, 10),
('Soap', 'Household', 12, 42.00, 5),
('Coffee', 'Beverage', 20, 28.00, 6),
('Eggs', 'Groceries', 18, 12.00, 7);