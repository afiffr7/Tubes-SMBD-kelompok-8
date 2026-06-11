<?php
$host = "localhost"; //port 3306, apache 8080(default gw 'riza')
$user = "root";
$pass = "";
$db   = "db_tubes";

// Cek apakah database sudah ada, kalau belum → auto setup
$conn_check = @mysqli_connect($host, $user, $pass, $db);
if (!$conn_check) {
    // Database belum ada, jalankan setup
    include __DIR__ . '/setup.php';
    // Coba connect lagi setelah setup
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn) {
        die("Setup database gagal. Pastikan MySQL sudah berjalan dan file database/db_tubes_umkm.sql ada.");
    }
} else {
    $conn = $conn_check;
}
mysqli_set_charset($conn, "utf8mb4");
?>
