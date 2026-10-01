-- Sari-Sari Store System Database Schema
-- Complete database structure with all required tables

-- Create Database
CREATE DATABASE IF NOT EXISTS sari_store_db;
USE sari_store_db;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL UNIQUE,
  category VARCHAR(100) NOT NULL,
  quantity INT NOT NULL DEFAULT 0,
  price DECIMAL(10, 2) NOT NULL,
  reorder_level INT NOT NULL DEFAULT 5,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sales Table (Transactions)
CREATE TABLE IF NOT EXISTS sales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  receipt_no VARCHAR(50) NOT NULL UNIQUE,
  sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  subtotal DECIMAL(10, 2) NOT NULL,
  discount DECIMAL(10, 2) DEFAULT 0,
  total DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_sale_date (sale_date),
  INDEX idx_receipt_no (receipt_no)
);

-- Sale Items Table (Line Items per Transaction)
CREATE TABLE IF NOT EXISTS sale_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  sale_id INT NOT NULL,
  product_id INT NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id),
  INDEX idx_sale_id (sale_id),
  INDEX idx_product_id (product_id)
);

-- Insert Sample Products
INSERT INTO products (name, category, quantity, price, reorder_level) VALUES
('Rice 5kg', 'Groceries', 25, 220.00, 8),
('Coke 1L', 'Beverage', 15, 65.00, 5),
('Instant Noodles', 'Snacks', 35, 18.00, 10),
('Soap', 'Household', 12, 42.00, 5),
('Coffee', 'Beverage', 20, 28.00, 6),
('Eggs', 'Groceries', 18, 12.00, 7);
