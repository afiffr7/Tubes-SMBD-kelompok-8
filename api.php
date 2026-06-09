<?php
session_start();
include 'koneksi.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// ===== HELPER: Cek apakah user sudah login =====
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
        exit;
    }
}

// ===== HELPER: Cek apakah user adalah admin =====
function requireAdmin() {
    requireLogin();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak! Hanya admin yang bisa melakukan aksi ini.']);
        exit;
    }
}

// ===== AUTH =====

if ($action == 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = $data['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            unset($user['password']);
            echo json_encode(['success' => true, 'user' => $user]);
        } else if ($password === $user['password']) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$newHash' WHERE id=" . $user['id']);

            // Set session
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            unset($user['password']);
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Username atau password salah.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username atau password salah.']);
    }
}

if ($action == 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $data['phone']);

    // Check if username exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Username sudah dipakai.']);
        exit;
    }

    $query = "INSERT INTO users (name, username, password, phone) VALUES ('$name', '$username', '$password', '$phone')";
    if (mysqli_query($conn, $query)) {
        $newUserId = mysqli_insert_id($conn);

        // Set session setelah register
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['role'] = 'user';
        $_SESSION['username'] = $username;

        echo json_encode(['success' => true, 'user' => ['id' => $newUserId, 'username' => $username, 'name' => $name, 'role' => 'user']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendaftar.']);
    }
}

if ($action == 'logout') {
    session_unset();
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logout berhasil.']);
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

// ===== CRUD STORES (Admin Only) =====

if ($action == 'add_store') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $mitra_id = mysqli_real_escape_string($conn, $data['mitra_id'] ?? '');
    $icon = mysqli_real_escape_string($conn, $data['icon'] ?? '🏪');
    $info = mysqli_real_escape_string($conn, $data['info'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Nama toko wajib diisi.']);
        exit;
    }

    $query = "INSERT INTO stores (name, mitra_id, icon, info) VALUES ('$name', " . ($mitra_id ? "'$mitra_id'" : "NULL") . ", '$icon', '$info')";
    if (mysqli_query($conn, $query)) {
        $newId = mysqli_insert_id($conn);
        echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Toko berhasil ditambahkan.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambah toko: ' . mysqli_error($conn)]);
    }
}

if ($action == 'update_store') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $mitra_id = mysqli_real_escape_string($conn, $data['mitra_id'] ?? '');
    $icon = mysqli_real_escape_string($conn, $data['icon'] ?? '🏪');
    $info = mysqli_real_escape_string($conn, $data['info'] ?? '');

    if ($id <= 0 || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID dan nama toko wajib diisi.']);
        exit;
    }

    $query = "UPDATE stores SET name='$name', mitra_id=" . ($mitra_id ? "'$mitra_id'" : "NULL") . ", icon='$icon', info='$info' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Toko berhasil diupdate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update toko: ' . mysqli_error($conn)]);
    }
}

if ($action == 'delete_store') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID toko tidak valid.']);
        exit;
    }

    // Hapus semua makanan toko ini dulu (cascade manual)
    mysqli_query($conn, "DELETE FROM foods WHERE store_id = $id");
    
    if (mysqli_query($conn, "DELETE FROM stores WHERE id = $id")) {
        echo json_encode(['success' => true, 'message' => 'Toko dan semua menunya berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus toko: ' . mysqli_error($conn)]);
    }
}

// ===== CRUD FOODS (Admin Only) =====

if ($action == 'add_food') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $store_id = (int)($data['store_id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $price = (int)($data['price'] ?? 0);

    if ($store_id <= 0 || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Store ID, nama, dan harga wajib diisi.']);
        exit;
    }

    $query = "INSERT INTO foods (store_id, name, price) VALUES ($store_id, '$name', $price)";
    if (mysqli_query($conn, $query)) {
        $newId = mysqli_insert_id($conn);
        echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Menu berhasil ditambahkan.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambah menu: ' . mysqli_error($conn)]);
    }
}

if ($action == 'update_food') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $price = (int)($data['price'] ?? 0);

    if ($id <= 0 || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID, nama, dan harga wajib diisi.']);
        exit;
    }

    $query = "UPDATE foods SET name='$name', price=$price WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Menu berhasil diupdate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update menu: ' . mysqli_error($conn)]);
    }
}

if ($action == 'delete_food') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID menu tidak valid.']);
        exit;
    }

    if (mysqli_query($conn, "DELETE FROM foods WHERE id = $id")) {
        echo json_encode(['success' => true, 'message' => 'Menu berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus menu: ' . mysqli_error($conn)]);
    }
}

// ===== CRUD MITRAS (Admin Only) =====

if ($action == 'add_mitra') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = mysqli_real_escape_string($conn, $data['id'] ?? '');
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');

    if (empty($id) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID dan nama mitra wajib diisi.']);
        exit;
    }

    // Cek duplikat
    $check = mysqli_query($conn, "SELECT id FROM mitras WHERE id = '$id'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'ID mitra sudah ada.']);
        exit;
    }

    $query = "INSERT INTO mitras (id, name) VALUES ('$id', '$name')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Mitra berhasil ditambahkan.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambah mitra: ' . mysqli_error($conn)]);
    }
}

if ($action == 'update_mitra') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = mysqli_real_escape_string($conn, $data['id'] ?? '');
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');

    if (empty($id) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID dan nama mitra wajib diisi.']);
        exit;
    }

    $query = "UPDATE mitras SET name='$name' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Mitra berhasil diupdate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update mitra: ' . mysqli_error($conn)]);
    }
}

if ($action == 'delete_mitra') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = mysqli_real_escape_string($conn, $data['id'] ?? '');

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID mitra tidak valid.']);
        exit;
    }

    // Cek apakah ada toko yang masih pakai mitra ini
    $check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM stores WHERE mitra_id = '$id'");
    $row = mysqli_fetch_assoc($check);
    if ($row['cnt'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Tidak bisa hapus mitra. Masih ada ' . $row['cnt'] . ' toko yang bermitra. Hapus toko-toko tersebut terlebih dahulu.']);
        exit;
    }

    if (mysqli_query($conn, "DELETE FROM mitras WHERE id = '$id'")) {
        echo json_encode(['success' => true, 'message' => 'Mitra berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus mitra: ' . mysqli_error($conn)]);
    }
}

if ($action == 'bulk_add_foods') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];
    
    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'Tidak ada data untuk diimport.']);
        exit;
    }
    
    $imported_stores = 0;
    $imported_foods = 0;
    
    mysqli_begin_transaction($conn);
    try {
        foreach ($items as $item) {
            $store_name = mysqli_real_escape_string($conn, $item['store']);
            $product_name = mysqli_real_escape_string($conn, $item['product']);
            $price = (int)$item['price'];
            $variant = mysqli_real_escape_string($conn, $item['variant']);
            
            // Cari atau buat toko
            $store_res = mysqli_query($conn, "SELECT id FROM stores WHERE name = '$store_name'");
            if (mysqli_num_rows($store_res) > 0) {
                $store = mysqli_fetch_assoc($store_res);
                $store_id = $store['id'];
            } else {
                // Auto-buat toko dengan icon 🥛
                $icon = '🥛';
                mysqli_query($conn, "INSERT INTO stores (name, icon, info) VALUES ('$store_name', '$icon', 'Toko hasil import')");
                $store_id = mysqli_insert_id($conn);
                $imported_stores++;
            }
            
            // Susun nama makanan: "Nama Produk - Varian rasa" jika berbeda
            $food_name = $product_name;
            if (strcasecmp($product_name, $variant) !== 0) {
                $food_name = $product_name . ' - ' . $variant;
            }
            
            // Cek duplikat menu di toko tersebut
            $food_check = mysqli_query($conn, "SELECT id FROM foods WHERE store_id = $store_id AND name = '$food_name'");
            if (mysqli_num_rows($food_check) == 0) {
                mysqli_query($conn, "INSERT INTO foods (store_id, name, price) VALUES ($store_id, '$food_name', $price)");
                $imported_foods++;
            }
        }
        mysqli_commit($conn);
        echo json_encode([
            'success' => true, 
            'message' => "Berhasil mengimport data: $imported_stores toko baru dibuat, $imported_foods menu baru ditambahkan."
        ]);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Gagal mengimport data: ' . $e->getMessage()]);
    }
}

// ===== ORDERS =====

if ($action == 'checkout') {
    requireLogin();

    $data = json_decode(file_get_contents('php://input'), true);
    // FIX #1: Ambil user_id dari session, BUKAN dari client
    $user_id = (int)$_SESSION['user_id'];
    $items = $data['items'] ?? [];

    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid. Keranjang kosong.']);
        exit;
    }

    // FIX #2: Hitung total_price di SERVER dari database, BUKAN dari client
    $total_price = 0;
    $validated_items = [];

    foreach ($items as $item) {
        $food_id = (int)($item['id'] ?? 0);
        $qty = (int)($item['qty'] ?? 0);

        if ($food_id <= 0 || $qty <= 0) {
            echo json_encode(['success' => false, 'message' => 'Item tidak valid: food_id atau qty kosong.']);
            exit;
        }

        // Query harga langsung dari database
        $food_query = mysqli_query($conn, "SELECT id, name, price FROM foods WHERE id = $food_id");
        if (mysqli_num_rows($food_query) == 0) {
            echo json_encode(['success' => false, 'message' => "Menu dengan ID $food_id tidak ditemukan."]);
            exit;
        }

        $food_row = mysqli_fetch_assoc($food_query);
        $server_price = (int)$food_row['price'];
        $server_name = $food_row['name'];

        $validated_items[] = [
            'food_id' => $food_id,
            'food_name' => $server_name,
            'qty' => $qty,
            'price' => $server_price
        ];

        $total_price += $server_price * $qty;
    }

    // Insert order dengan harga yang dihitung server
    if (mysqli_query($conn, "INSERT INTO orders (user_id, total_price) VALUES ($user_id, $total_price)")) {
        $order_id = mysqli_insert_id($conn);
        foreach ($validated_items as $vi) {
            $food_id = $vi['food_id'];
            $food_name = mysqli_real_escape_string($conn, $vi['food_name']);
            $qty = $vi['qty'];
            $price = $vi['price'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, food_id, food_name, qty, price) VALUES ($order_id, $food_id, '$food_name', $qty, $price)");
        }
        echo json_encode(['success' => true, 'message' => 'Pesanan berhasil dibuat.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal membuat pesanan.']);
    }
}

if ($action == 'get_orders') {
    requireLogin();

    // FIX #3: Ambil user_id dari session, BUKAN dari $_GET (IDOR fix)
    $user_id = (int)$_SESSION['user_id'];

    $query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    $orders_q = mysqli_query($conn, $query);
    $orders = mysqli_fetch_all($orders_q, MYSQLI_ASSOC);
    
    foreach ($orders as &$o) {
        $oid = $o['id'];
        $items_q = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $oid");
        $o['items'] = mysqli_fetch_all($items_q, MYSQLI_ASSOC);
    }
    echo json_encode(['success' => true, 'orders' => $orders]);
}
?>