<?php
/**
 * Auto-setup: Membuat database db_tubes dan semua tabel yang diperlukan.
 * Dipanggil otomatis oleh koneksi.php jika database belum ada.
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_tubes";

// Koneksi tanpa database dulu
$conn_setup = mysqli_connect($host, $user, $pass);
if (!$conn_setup) {
    die("Koneksi MySQL gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn_setup, "utf8mb4");

// Buat database
mysqli_query($conn_setup, "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

// Pilih database
mysqli_select_db($conn_setup, $db);

// Import SQL dump jika tabel utama belum ada
$check = mysqli_query($conn_setup, "SHOW TABLES LIKE 'produk'");
if (mysqli_num_rows($check) == 0) {
    $sqlFile = __DIR__ . '/database/db_tubes_umkm.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        mysqli_multi_query($conn_setup, $sql);
        // Flush semua result dari multi_query
        while (mysqli_next_result($conn_setup)) {;}
    }
}

// Buat tabel users jika belum ada
mysqli_query($conn_setup, "CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `role` varchar(20) DEFAULT 'user',
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Buat tabel orders jika belum ada
mysqli_query($conn_setup, "CREATE TABLE IF NOT EXISTS `orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `total_price` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Buat tabel order_items jika belum ada
mysqli_query($conn_setup, "CREATE TABLE IF NOT EXISTS `order_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `food_id` int(11) NOT NULL,
    `food_name` varchar(100) DEFAULT NULL,
    `qty` int(11) NOT NULL,
    `price` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Insert default admin jika belum ada
$checkAdmin = mysqli_query($conn_setup, "SELECT id FROM users WHERE role='admin' LIMIT 1");
if (mysqli_num_rows($checkAdmin) == 0) {
    $defaultPass = password_hash('123', PASSWORD_DEFAULT);
    mysqli_query($conn_setup, "INSERT INTO users (name, username, password, phone, role) VALUES ('riza', 'riza', '$defaultPass', NULL, 'admin')");
}

mysqli_close($conn_setup);
?>
