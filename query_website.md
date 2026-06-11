# Dokumentasi Query SQL - Tugas Besar SMBD

Dokumen ini berisi seluruh query SQL yang digunakan pada sistem website pemesanan makanan berbasis UMKM. Untuk mempermudah penilaian, dokumen ini telah dibagi berdasarkan query lama bawaan sistem Anda (`db_tubes_umkm.sql`, `setup.php`, dan `api.php`) serta query pengembangan baru yang ditambahkan untuk memenuhi kebutuhan Sistem Manajemen Basis Data (SMBD).

Setiap query ditulis menggunakan standar penulisan SQL aman (menggunakan placeholder `?` untuk pencegahan *SQL Injection* pada driver PHP MySQLi/PDO).

---

## 1. Query DDL (Data Definition Language) & DML Dasar

### A. Query Pembuatan Tabel (DDL)

#### `[DDL LAMA]` - Berasal dari file `db_tubes_umkm.sql`
Tabel-tabel ini merupakan struktur basis data utama hasil rancangan awal Anda.

```sql
-- 1. Tabel Jenis Kategori
CREATE TABLE `jenis` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tabel Varian Rasa
CREATE TABLE `varian_rasa` (
  `id_rasa` int(11) NOT NULL AUTO_INCREMENT,
  `nama_rasa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rasa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Tabel Metode Pembayaran
CREATE TABLE `metode_pembayaran` (
  `id_metode` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pembayaran` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_metode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Tabel Mitra Delivery Online
CREATE TABLE `mitra` (
  `id_mitra` int(11) NOT NULL AUTO_INCREMENT,
  `nama_mitra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_mitra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Tabel UMKM (Profil Toko)
CREATE TABLE `umkm` (
  `id_umkm` int(11) NOT NULL AUTO_INCREMENT,
  `nama_umkm` varchar(255) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `kontak_umkm` varchar(255) DEFAULT NULL,
  `sertifikasi_halal` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `link_gmaps` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_umkm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6. Tabel Relasi Many-to-Many: Mitra Ojol dengan UMKM
CREATE TABLE `mitra_umkm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_umkm` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `link_mitra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. Tabel Relasi Many-to-Many: Metode Pembayaran didukung UMKM
CREATE TABLE `pembayaran_umkm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_umkm` int(11) NOT NULL,
  `id_metode` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (`id_metode`) REFERENCES `metode_pembayaran` (`id_metode`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. Tabel Produk Menu Makanan/Minuman
CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT,
  `id_umkm` int(11) NOT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `id_jenis` int(11) NOT NULL,
  `harga` decimal(11,2) DEFAULT NULL,
  `id_varian` int(11) NOT NULL,
  PRIMARY KEY (`id_produk`),
  FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON UPDATE CASCADE,
  FOREIGN KEY (`id_varian`) REFERENCES `varian_rasa` (`id_rasa`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### `[DDL BARU / PELENGKAP]` - Berasal dari file `setup.php`
Tabel-tabel transaksi dan pengelolaan akun pengguna untuk mendukung fungsionalitas aplikasi dinamis.

```sql
-- 9. Tabel User Akun (Pelanggan & Admin)
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `role` varchar(20) DEFAULT 'user',
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Tabel Orders (Data Transaksi Utama)
CREATE TABLE IF NOT EXISTS `orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `total_price` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Tabel Order Items (Rincian Produk Belanjaan)
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `food_id` int(11) NOT NULL,
    `food_name` varchar(100) DEFAULT NULL,
    `qty` int(11) NOT NULL,
    `price` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### B. Query Pengisian Data Awal (DML Seeding)

#### `[DML SEEDING LAMA]` - Pengisian Kategori Bawaan Database
```sql
-- Memasukkan Jenis Kategori
INSERT INTO `jenis` VALUES (1,'Makanan'),(2,'Minuman'),(3,'Topping');

-- Memasukkan Varian Rasa
INSERT INTO `varian_rasa` VALUES (1,'Manis'),(2,'Asin'),(3,'Pedas'),(4,'Asam'),(5,'Pahit'),(6,'Netral');

-- Memasukkan Metode Pembayaran
INSERT INTO `metode_pembayaran` VALUES (1,'Cash'),(2,'Qris'),(3,'Transfer');

-- Memasukkan Daftar Ojek Online
INSERT INTO `mitra` VALUES (1,'Gojek'),(2,'Shopee'),(3,'Grab');
```

#### `[DML SEEDING BARU / SETUP]` - Inisialisasi Akun Admin Pertama
```sql
-- Membuat akun admin riza apabila belum terdaftar
INSERT INTO users (name, username, password, phone, role) 
VALUES ('riza', 'riza', '$2y$10$TKh8H1...', NULL, 'admin');
```

---

## 2. Query Fitur Autentikasi & User Management

### `[QUERY AUTH LAMA]` - Fungsionalitas Login & Register Awal
Query yang sudah terimplementasi di web Anda untuk alur autentikasi pengunjung.

#### Registrasi Akun Pelanggan Baru (`api.php?action=register`)
```sql
INSERT INTO users (name, username, password, phone, role) 
VALUES (?, ?, ?, ?, 'user');
```

#### Validasi Data Login Pengguna (`api.php?action=login`)
```sql
SELECT * FROM users WHERE username = '$username';
```

---

### `[QUERY AUTH BARU]` - Fitur Manajemen Akun Tambahan
Fitur proteksi dan pengaturan akun untuk kelengkapan platform.

#### Update Profil Akun Pengguna
```sql
UPDATE users SET name = ?, phone = ? WHERE id = ?;
```

#### Penggantian Password Pengguna (Secure Update)
```sql
UPDATE users SET password = ? WHERE id = ?;
```

#### Mengangkat Akun User Biasa Menjadi Admin (Role Upgrade)
```sql
UPDATE users SET role = 'admin' WHERE id = ?;
```

---

## 3. Query Operasi Fitur Utama Website (CRUD)

### `[CRUD LAMA]` - Operasi yang Sudah Berjalan di Website Anda
Query bawaan sistem untuk manajemen UMKM, Produk, dan transaksi Checkout.

#### Tambah Toko UMKM Baru (`api.php?action=add_store`)
```sql
INSERT INTO umkm (nama_umkm, alamat) VALUES ('$name', '$info');
```

#### Hapus Toko UMKM (`api.php?action=delete_store`)
```sql
DELETE FROM umkm WHERE id_umkm = $id;
```

#### Tambah Menu Produk Baru (`api.php?action=add_food`)
```sql
INSERT INTO produk (id_umkm, nama_produk, id_jenis, harga, id_varian) 
VALUES ($store_id, '$name', $id_jenis, $price, $id_varian);
```

#### Hapus Menu Produk (`api.php?action=delete_food`)
```sql
DELETE FROM produk WHERE id_produk = $id;
```

#### Proses Checkout Pesanan Pelanggan (`api.php?action=checkout`)
```sql
-- 1. Insert header pesanan
INSERT INTO orders (user_id, total_price) VALUES ($user_id, $total_price);

-- 2. Insert item-item pesanan
INSERT INTO order_items (order_id, food_id, food_name, qty, price) 
VALUES ($order_id, $food_id, '$food_name', $qty, $price);
```

---

### `[CRUD BARU]` - Operasi Read & Update yang Ditingkatkan
Query terstruktur untuk pengisian form dan pembaruan data secara dinamis.

#### Mengambil Detail Lengkap Satu UMKM untuk Halaman Sunting/Edit
```sql
SELECT id_umkm, nama_umkm, jam_buka, jam_tutup, kontak_umkm, sertifikasi_halal, foto, alamat, link_gmaps 
FROM umkm 
WHERE id_umkm = ?;
```

#### Update Informasi Toko UMKM
```sql
UPDATE umkm 
SET nama_umkm = ?, jam_buka = ?, jam_tutup = ?, kontak_umkm = ?, sertifikasi_halal = ?, foto = ?, alamat = ?, link_gmaps = ? 
WHERE id_umkm = ?;
```

#### Mengambil Rincian Menu Produk Tertentu untuk Form Sunting
```sql
SELECT id_produk, id_umkm, nama_produk, id_jenis, harga, id_varian 
FROM produk 
WHERE id_produk = ?;
```

#### Update Rincian Menu Produk
```sql
UPDATE produk 
SET nama_produk = ?, id_jenis = ?, harga = ?, id_varian = ? 
WHERE id_produk = ?;
```

---

## 4. Query Relasi & Join (Untuk Tampilan Halaman/Tabel Berelasi)

### `[RELASI LAMA]` - Query Tampilan Sederhana
Query relasi minimal yang dipakai sistem untuk memuat data awal.

#### Mengambil Semua Produk Terhubung dengan Jenis Kategori (`api.php?action=get_data`)
```sql
SELECT p.id_produk, p.id_umkm, p.nama_produk, p.harga, j.nama_jenis 
FROM produk p 
LEFT JOIN jenis j ON p.id_jenis = j.id_jenis;
```

#### Mengambil Satu Tautan Mitra Pengiriman per UMKM (`api.php?action=get_data`)
```sql
SELECT id_mitra FROM mitra_umkm WHERE id_umkm = $id_umkm LIMIT 1;
```

---

### `[RELASI BARU / PENGEMBANGAN]` - Query Join Kompleks
Penggunaan Join tingkat lanjut untuk menampilkan laporan data secara komprehensif.

#### Menampilkan Katalog Produk Lengkap dengan Kategori dan Varian Rasa
Query ini menggabungkan 4 tabel sekaligus agar pelanggan mendapatkan info produk, nama tokonya, kategori menu, dan varian rasanya secara detail.
```sql
SELECT 
    p.id_produk, 
    p.nama_produk, 
    p.harga, 
    j.nama_jenis AS kategori, 
    v.nama_rasa AS varian, 
    u.nama_umkm AS nama_toko
FROM produk p
INNER JOIN jenis j ON p.id_jenis = j.id_jenis
INNER JOIN varian_rasa v ON p.id_varian = v.id_rasa
INNER JOIN umkm u ON p.id_umkm = u.id_umkm
ORDER BY u.nama_umkm ASC, p.nama_produk ASC;
```

#### Menampilkan Daftar UMKM dengan Seluruh Metode Pembayaran yang Diterima (Menggunakan GROUP_CONCAT)
Query relasi Many-to-Many untuk merangkum seluruh metode pembayaran (Cash, QRIS, dll) yang didukung oleh setiap UMKM ke dalam satu baris teks.
```sql
SELECT 
    u.id_umkm, 
    u.nama_umkm, 
    u.alamat,
    GROUP_CONCAT(mp.nama_pembayaran ORDER BY mp.nama_pembayaran SEPARATOR ', ') AS metode_pembayaran
FROM umkm u
LEFT JOIN pembayaran_umkm pu ON u.id_umkm = pu.id_umkm
LEFT JOIN metode_pembayaran mp ON pu.id_metode = mp.id_metode
GROUP BY u.id_umkm
ORDER BY u.nama_umkm ASC;
```

#### Menampilkan Tautan Kemitraan Ojek Online Milik Toko UMKM
```sql
SELECT 
    u.nama_umkm, 
    m.nama_mitra AS platform_online, 
    mu.link_mitra AS url_restoran
FROM umkm u
INNER JOIN mitra_umkm mu ON u.id_umkm = mu.id_umkm
INNER JOIN mitra m ON mu.id_mitra = m.id_mitra
WHERE u.id_umkm = ?;
```


#### Fitur Pencarian Dinamis dengan Pencocokan Teks & Rentang Harga
Query filter pencarian produk berdasarkan kemiripan nama makanan/nama toko, rentang harga, dan filter jenis kategori secara bersamaan.
```sql
SELECT 
    p.id_produk, 
    p.nama_produk, 
    p.harga, 
    u.nama_umkm, 
    j.nama_jenis AS kategori
FROM produk p
INNER JOIN umkm u ON p.id_umkm = u.id_umkm
INNER JOIN jenis j ON p.id_jenis = j.id_jenis
WHERE (p.nama_produk LIKE ? OR u.nama_umkm LIKE ?)
  AND p.harga BETWEEN ? AND ?
  AND (? = 0 OR p.id_jenis = ?)
ORDER BY p.harga ASC;
```

#### Mengambil Riwayat Transaksi Pengguna Beserta Rincian Item yang Dipesan
Mengambil rekap belanja user berserta detail kuantitas dan harga satuan item makanan yang dibeli.
```sql
SELECT 
    o.id AS order_id, 
    o.created_at AS tanggal_transaksi, 
    o.total_price AS total_bayar, 
    oi.food_name AS nama_menu, 
    oi.qty AS jumlah_item, 
    oi.price AS harga_satuan,
    (oi.qty * oi.price) AS subtotal
FROM orders o
INNER JOIN order_items oi ON o.id = oi.order_id
WHERE o.user_id = ?
ORDER BY o.created_at DESC;
```

---

## 5. Query Agregasi & Analitik (Untuk Dashboard)

### `[AGREGASI LAMA]` - Statistik Dasar Dashboard
Query hitung kuantitas entitas dasar secara individu yang ada di versi sistem awal.

#### Menghitung Jumlah Total Produk di Website
```sql
SELECT COUNT(*) as c FROM produk;
```

#### Menghitung Jumlah Total Toko UMKM Terdaftar
```sql
SELECT COUNT(*) as c FROM umkm;
```

---

### `[AGREGASI BARU / PENGEMBANGAN]` - Dashboard Analitik Lanjutan
Query optimasi dan analisis performa bisnis kuliner di website untuk kebutuhan laporan akademik.

#### 1. Ringkasan Statistik Utama Dashboard Admin (Single Query Optimization)
Menggabungkan perhitungan total UMKM, produk, pengguna, transaksi, dan total omset pendapatan ke dalam satu kali eksekusi database.
```sql
SELECT 
    (SELECT COUNT(*) FROM umkm) AS total_umkm,
    (SELECT COUNT(*) FROM produk) AS total_produk,
    (SELECT COUNT(*) FROM users) AS total_pengguna,
    (SELECT COUNT(*) FROM orders) AS total_transaksi,
    (SELECT COALESCE(SUM(total_price), 0) FROM orders) AS total_omset_penjualan;
```

#### 2. Distribusi Menu Makanan Terbanyak Berdasarkan UMKM (Top 10 UMKM)
Menganalisis dan meranking UMKM mana saja yang paling aktif menyediakan ragam variasi menu produk makanan/minuman.
```sql
SELECT 
    u.nama_umkm, 
    COUNT(p.id_produk) AS jumlah_produk
FROM umkm u
LEFT JOIN produk p ON u.id_umkm = p.id_umkm
GROUP BY u.id_umkm
ORDER BY jumlah_produk DESC
LIMIT 10;
```

#### 3. Distribusi Produk Kuliner Berdasarkan Kategori (Jenis)
Menghitung sebaran menu produk di sistem berdasarkan pengelompokan jenis kategori (makanan, minuman, topping).
```sql
SELECT 
    j.nama_jenis, 
    COUNT(p.id_produk) AS jumlah_produk
FROM jenis j
LEFT JOIN produk p ON j.id_jenis = p.id_jenis
GROUP BY j.id_jenis
ORDER BY jumlah_produk DESC;
```

#### 4. Produk Terlaris (*Best Seller Items*)
Menganalisis menu kuliner apa saja yang paling disukai dan paling sering dipesan oleh pelanggan beserta total pendapatan yang disumbangkan.
```sql
SELECT 
    food_name AS nama_produk, 
    SUM(qty) AS total_terjual, 
    SUM(qty * price) AS total_omset_produk
FROM order_items
GROUP BY food_id, food_name
ORDER BY total_terjual DESC
LIMIT 5;
```

#### 5. Rata-rata Nilai Belanja Per Transaksi (*Average Order Value*)
Mengukur rata-rata daya beli pelanggan di website kuliner ini untuk setiap transaksi yang terjadi.
```sql
SELECT 
    ROUND(AVG(total_price), 2) AS rata_rata_belanja 
FROM orders;
```
