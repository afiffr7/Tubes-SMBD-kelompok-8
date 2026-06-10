<?php
$host = "localhost"; //port 3306, apache 8080(default gw 'riza')
$user = "root";
$pass = "";
$db   = "db_tubes";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
