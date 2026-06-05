
-- ini buat anuan database dkk dll ygy

CREATE DATABASE IF NOT EXISTS db_pemesanan_makanan;
USE db_pemesanan_makanan;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS mitras (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    mitra_id VARCHAR(50),
    icon VARCHAR(10),
    info TEXT,
    FOREIGN KEY (mitra_id) REFERENCES mitras(id)
);

CREATE TABLE IF NOT EXISTS foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    FOREIGN KEY (store_id) REFERENCES stores(id)
);

INSERT INTO mitras (id, name) VALUES 
('gojek', 'Gojek'),
('grab', 'Grab'),
('shopee', 'ShopeeFood');

INSERT INTO stores (name, mitra_id, icon, info) VALUES 
('Warung Nasi Padang', 'gojek', '🍛', 'Masakan padang asli & lezat'),
('Burger Kingdom', 'grab', '🍔', 'Burger premium & fresh'),
('Sushi Master', 'shopee', '🍣', 'Sushi Jepang autentik'),
('Pizza Paradise', 'gojek', '🍕', 'Pizza Italia sejati'),
('Kopi Kenangan', 'grab', '☕', 'Kopi lokal kekinian'),
('Bakso Pak Kumis', 'shopee', '🍜', 'Bakso sapi pilihan');


-- tumbal sementara
INSERT INTO foods (store_id, name, price) VALUES 
(1, 'Nasi Rendang', 25000), (1, 'Ayam Pop', 20000), (1, 'Gulai Tunjang', 22000);

INSERT INTO foods (store_id, name, price) VALUES 
(2, 'Beef Burger', 35000), (2, 'Cheese Burger', 40000), (2, 'Chicken Burger', 32000);

INSERT INTO foods (store_id, name, price) VALUES 
(3, 'Salmon Sushi', 45000), (3, 'Tuna Roll', 40000), (3, 'California Roll', 38000);

INSERT INTO foods (store_id, name, price) VALUES 
(4, 'Pepperoni Pizza', 65000), (4, 'Margherita Pizza', 55000), (4, 'Hawaiian Pizza', 60000);

INSERT INTO foods (store_id, name, price) VALUES 
(5, 'Es Kopi Susu', 18000), (5, 'Americano', 15000), (5, 'Cappuccino', 20000);

INSERT INTO foods (store_id, name, price) VALUES 
(6, 'Bakso Urat', 20000), (6, 'Mie Ayam', 17000), (6, 'Bakso Komplit', 25000);

-- Default User, optional sih
INSERT INTO users (name, username, password) VALUES ('Admin', 'admin', 'admin');
