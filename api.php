<?php
include 'koneksi.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action == 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = $data['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Username atau password salah.']);
    }
}

if ($action == 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);

    // Check if username exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Username sudah dipakai.']);
        exit;
    }

    $query = "INSERT INTO users (name, username, password, phone) VALUES ('$name', '$username', '$password', '$phone')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'user' => ['username' => $username, 'name' => $name]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendaftar.']);
    }
}

if ($action == 'get_data') {
    $mitras = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM mitras"), MYSQLI_ASSOC);
    $stores = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM stores"), MYSQLI_ASSOC);
    $foods_raw = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM foods"), MYSQLI_ASSOC);

    $foods = [];
    foreach ($foods_raw as $f) {
        $foods[$f['store_id']][] = $f;
    }

    echo json_encode([
        'mitras' => $mitras,
        'stores' => $stores,
        'foods' => $foods
    ]);
}
?>