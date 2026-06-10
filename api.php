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
    // Ambil mitra dari tabel baru
    $mitras_raw = mysqli_fetch_all(mysqli_query($conn, "SELECT id_mitra, nama_mitra FROM mitra"), MYSQLI_ASSOC);
    $mitras = [];
    foreach ($mitras_raw as $m) {
        $mitras[] = ['id' => $m['id_mitra'], 'name' => $m['nama_mitra']];
    }

    // Ambil toko (umkm) - map ke format lama
    $stores_raw = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM umkm"), MYSQLI_ASSOC);
    $stores = [];
    foreach ($stores_raw as $s) {
        // Cari mitra_id dari mitra_umkm (ambil yang pertama saja)
        $mu = mysqli_query($conn, "SELECT id_mitra FROM mitra_umkm WHERE id_umkm = " . (int)$s['id_umkm'] . " LIMIT 1");
        $mitra_id = null;
        if ($mu && mysqli_num_rows($mu) > 0) {
            $mrow = mysqli_fetch_assoc($mu);
            $mitra_id = $mrow['id_mitra'];
        }
        $stores[] = [
            'id' => $s['id_umkm'],
            'name' => $s['nama_umkm'],
            'mitra_id' => $mitra_id,
            'icon' => $s['foto'] ? '🏪' : '🏪',
            'info' => $s['alamat'] ?? ''
        ];
    }

    // Ambil produk + join jenis untuk nama jenis
    $foods_raw = mysqli_fetch_all(mysqli_query($conn, "
        SELECT p.id_produk, p.id_umkm, p.nama_produk, p.harga, j.nama_jenis 
        FROM produk p 
        LEFT JOIN jenis j ON p.id_jenis = j.id_jenis
    "), MYSQLI_ASSOC);

    $foods = [];
    foreach ($foods_raw as $f) {
        $foods[$f['id_umkm']][] = [
            'id' => (int)$f['id_produk'],
            'name' => $f['nama_produk'],
            'price' => (int)$f['harga'],
            'jenis' => $f['nama_jenis'] ?? 'Makanan'
        ];
    }

    echo json_encode([
        'mitras' => $mitras,
        'stores' => $stores,
        'foods' => $foods
    ]);
}

// ===== CRUD STORES (Admin Only) - mapped to umkm =====

if ($action == 'add_store') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $mitra_id = isset($data['mitra_id']) ? (int)$data['mitra_id'] : 0;
    $info = mysqli_real_escape_string($conn, $data['info'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Nama toko wajib diisi.']);
        exit;
    }

    $query = "INSERT INTO umkm (nama_umkm, alamat) VALUES ('$name', '$info')";
    if (mysqli_query($conn, $query)) {
        $newId = mysqli_insert_id($conn);
        // Link mitra if provided
        if ($mitra_id > 0) {
            mysqli_query($conn, "INSERT INTO mitra_umkm (id_umkm, id_mitra) VALUES ($newId, $mitra_id)");
        }
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
    $mitra_id = isset($data['mitra_id']) ? (int)$data['mitra_id'] : 0;
    $info = mysqli_real_escape_string($conn, $data['info'] ?? '');

    if ($id <= 0 || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID dan nama toko wajib diisi.']);
        exit;
    }

    $query = "UPDATE umkm SET nama_umkm='$name', alamat='$info' WHERE id_umkm=$id";
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

    // Hapus produk, mitra_umkm, pembayaran_umkm dulu
    mysqli_query($conn, "DELETE FROM produk WHERE id_umkm = $id");
    mysqli_query($conn, "DELETE FROM mitra_umkm WHERE id_umkm = $id");
    mysqli_query($conn, "DELETE FROM pembayaran_umkm WHERE id_umkm = $id");
    
    if (mysqli_query($conn, "DELETE FROM umkm WHERE id_umkm = $id")) {
        echo json_encode(['success' => true, 'message' => 'Toko dan semua menunya berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus toko: ' . mysqli_error($conn)]);
    }
}

// ===== CRUD FOODS (Admin Only) - mapped to produk =====

if ($action == 'add_food') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $store_id = (int)($data['store_id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $price = (int)($data['price'] ?? 0);
    $jenis = mysqli_real_escape_string($conn, $data['jenis'] ?? 'Makanan');

    if ($store_id <= 0 || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Store ID, nama, dan harga wajib diisi.']);
        exit;
    }

    // Map jenis string to id_jenis
    $id_jenis = 1; // Default: Makanan
    if ($jenis == 'Minuman') $id_jenis = 2;
    else if ($jenis == 'Topping') $id_jenis = 3;

    // Default varian = Netral (6)
    $id_varian = 6;

    $query = "INSERT INTO produk (id_umkm, nama_produk, id_jenis, harga, id_varian) VALUES ($store_id, '$name', $id_jenis, $price, $id_varian)";
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
    $jenis = mysqli_real_escape_string($conn, $data['jenis'] ?? 'Makanan');

    if ($id <= 0 || empty($name) || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID, nama, dan harga wajib diisi.']);
        exit;
    }

    // Map jenis string to id_jenis
    $id_jenis = 1;
    if ($jenis == 'Minuman') $id_jenis = 2;
    else if ($jenis == 'Topping') $id_jenis = 3;

    $query = "UPDATE produk SET nama_produk='$name', harga=$price, id_jenis=$id_jenis WHERE id_produk=$id";
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

    if (mysqli_query($conn, "DELETE FROM produk WHERE id_produk = $id")) {
        echo json_encode(['success' => true, 'message' => 'Menu berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus menu: ' . mysqli_error($conn)]);
    }
}

// ===== CRUD MITRAS (Admin Only) - mapped to mitra =====

if ($action == 'add_mitra') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Nama mitra wajib diisi.']);
        exit;
    }

    $query = "INSERT INTO mitra (nama_mitra) VALUES ('$name')";
    if (mysqli_query($conn, $query)) {
        $newId = mysqli_insert_id($conn);
        echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Mitra berhasil ditambahkan.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambah mitra: ' . mysqli_error($conn)]);
    }
}

if ($action == 'update_mitra') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');

    if ($id <= 0 || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'ID dan nama mitra wajib diisi.']);
        exit;
    }

    $query = "UPDATE mitra SET nama_mitra='$name' WHERE id_mitra=$id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Mitra berhasil diupdate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update mitra: ' . mysqli_error($conn)]);
    }
}

if ($action == 'delete_mitra') {
    requireAdmin();

    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID mitra tidak valid.']);
        exit;
    }

    // Cek apakah ada umkm yang masih pakai mitra ini
    $check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM mitra_umkm WHERE id_mitra = $id");
    $row = mysqli_fetch_assoc($check);
    if ($row['cnt'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Tidak bisa hapus mitra. Masih ada ' . $row['cnt'] . ' toko yang bermitra. Hapus toko-toko tersebut terlebih dahulu.']);
        exit;
    }

    if (mysqli_query($conn, "DELETE FROM mitra WHERE id_mitra = $id")) {
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
            $jenis = mysqli_real_escape_string($conn, $item['jenis'] ?? 'Makanan');
            
            // Map jenis to id_jenis
            $id_jenis = 1;
            if ($jenis == 'Minuman') $id_jenis = 2;
            else if ($jenis == 'Topping') $id_jenis = 3;
            
            // Cari atau buat toko (umkm)
            $store_res = mysqli_query($conn, "SELECT id_umkm FROM umkm WHERE nama_umkm = '$store_name'");
            if (mysqli_num_rows($store_res) > 0) {
                $store = mysqli_fetch_assoc($store_res);
                $store_id = $store['id_umkm'];
            } else {
                mysqli_query($conn, "INSERT INTO umkm (nama_umkm, alamat) VALUES ('$store_name', 'Toko hasil import')");
                $store_id = mysqli_insert_id($conn);
                $imported_stores++;
            }
            
            // Susun nama makanan
            $food_name = $product_name;
            if (strcasecmp($product_name, $variant) !== 0) {
                $food_name = $product_name . ' - ' . $variant;
            }
            
            // Default varian = Netral (6)
            $id_varian = 6;
            
            // Cek duplikat
            $food_check = mysqli_query($conn, "SELECT id_produk FROM produk WHERE id_umkm = $store_id AND nama_produk = '$food_name'");
            if (mysqli_num_rows($food_check) == 0) {
                mysqli_query($conn, "INSERT INTO produk (id_umkm, nama_produk, id_jenis, harga, id_varian) VALUES ($store_id, '$food_name', $id_jenis, $price, $id_varian)");
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
    $user_id = (int)$_SESSION['user_id'];
    $items = $data['items'] ?? [];

    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid. Keranjang kosong.']);
        exit;
    }

    $total_price = 0;
    $validated_items = [];

    foreach ($items as $item) {
        $food_id = (int)($item['id'] ?? 0);
        $qty = (int)($item['qty'] ?? 0);

        if ($food_id <= 0 || $qty <= 0) {
            echo json_encode(['success' => false, 'message' => 'Item tidak valid: food_id atau qty kosong.']);
            exit;
        }

        // Query harga dari tabel produk
        $food_query = mysqli_query($conn, "SELECT id_produk, nama_produk, harga FROM produk WHERE id_produk = $food_id");
        if (mysqli_num_rows($food_query) == 0) {
            echo json_encode(['success' => false, 'message' => "Menu dengan ID $food_id tidak ditemukan."]);
            exit;
        }

        $food_row = mysqli_fetch_assoc($food_query);
        $server_price = (int)$food_row['harga'];
        $server_name = $food_row['nama_produk'];

        $validated_items[] = [
            'food_id' => $food_id,
            'food_name' => $server_name,
            'qty' => $qty,
            'price' => $server_price
        ];

        $total_price += $server_price * $qty;
    }

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

// ===== ADMIN STATS =====

if ($action == 'get_stats') {
    requireAdmin();

    $total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM produk"))['c'];
    $total_umkm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM umkm"))['c'];
    $total_mitra = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM mitra"))['c'];
    $total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM orders"))['c'];
    $total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];

    // Produk per UMKM (top 10 untuk chart)
    $chart_raw = mysqli_fetch_all(mysqli_query($conn, "
        SELECT u.nama_umkm, COUNT(p.id_produk) as jumlah
        FROM umkm u
        LEFT JOIN produk p ON u.id_umkm = p.id_umkm
        GROUP BY u.id_umkm
        ORDER BY jumlah DESC
        LIMIT 10
    "), MYSQLI_ASSOC);

    // Produk per jenis
    $jenis_raw = mysqli_fetch_all(mysqli_query($conn, "
        SELECT j.nama_jenis, COUNT(p.id_produk) as jumlah
        FROM jenis j
        LEFT JOIN produk p ON j.id_jenis = p.id_jenis
        GROUP BY j.id_jenis
    "), MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_produk' => (int)$total_produk,
            'total_umkm' => (int)$total_umkm,
            'total_mitra' => (int)$total_mitra,
            'total_orders' => (int)$total_orders,
            'total_users' => (int)$total_users,
        ],
        'chart_produk_per_umkm' => $chart_raw,
        'chart_produk_per_jenis' => $jenis_raw,
    ]);
}

// ===== ADMIN DATA TABLE =====

if ($action == 'get_table_data') {
    requireAdmin();

    $table = $_GET['table'] ?? '';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = max(5, min(100, (int)($_GET['per_page'] ?? 10)));
    $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
    $sortCol = $_GET['sort'] ?? '';
    $sortDir = strtoupper($_GET['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

    $offset = ($page - 1) * $perPage;
    $rows = [];
    $total = 0;
    $columns = [];

    if ($table === 'produk') {
        $columns = ['id_produk', 'nama_produk', 'harga', 'nama_jenis', 'nama_umkm'];
        $where = $search ? "WHERE p.nama_produk LIKE '%$search%' OR j.nama_jenis LIKE '%$search%' OR u.nama_umkm LIKE '%$search%'" : "";

        $allowedSort = ['id_produk'=>'p.id_produk', 'nama_produk'=>'p.nama_produk', 'harga'=>'p.harga', 'nama_jenis'=>'j.nama_jenis', 'nama_umkm'=>'u.nama_umkm'];
        $orderBy = isset($allowedSort[$sortCol]) ? "ORDER BY {$allowedSort[$sortCol]} $sortDir" : "ORDER BY p.id_produk ASC";

        $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM produk p LEFT JOIN jenis j ON p.id_jenis=j.id_jenis LEFT JOIN umkm u ON p.id_umkm=u.id_umkm $where"))['c'];
        $rows = mysqli_fetch_all(mysqli_query($conn, "
            SELECT p.id_produk, p.nama_produk, p.harga, j.nama_jenis, u.nama_umkm, p.id_umkm
            FROM produk p
            LEFT JOIN jenis j ON p.id_jenis = j.id_jenis
            LEFT JOIN umkm u ON p.id_umkm = u.id_umkm
            $where $orderBy LIMIT $perPage OFFSET $offset
        "), MYSQLI_ASSOC);

    } else if ($table === 'umkm') {
        $columns = ['id_umkm', 'nama_umkm', 'alamat', 'kontak_umkm', 'sertifikasi_halal'];
        $where = $search ? "WHERE nama_umkm LIKE '%$search%' OR alamat LIKE '%$search%'" : "";

        $allowedSort = ['id_umkm'=>'id_umkm', 'nama_umkm'=>'nama_umkm', 'alamat'=>'alamat', 'kontak_umkm'=>'kontak_umkm'];
        $orderBy = isset($allowedSort[$sortCol]) ? "ORDER BY {$allowedSort[$sortCol]} $sortDir" : "ORDER BY id_umkm ASC";

        $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM umkm $where"))['c'];
        $rows = mysqli_fetch_all(mysqli_query($conn, "SELECT id_umkm, nama_umkm, alamat, kontak_umkm, sertifikasi_halal FROM umkm $where $orderBy LIMIT $perPage OFFSET $offset"), MYSQLI_ASSOC);

    } else if ($table === 'mitra') {
        $columns = ['id_mitra', 'nama_mitra', 'jumlah_umkm'];
        $where = $search ? "HAVING nama_mitra LIKE '%$search%'" : "";

        $allowedSort = ['id_mitra'=>'m.id_mitra', 'nama_mitra'=>'m.nama_mitra', 'jumlah_umkm'=>'jumlah_umkm'];
        $orderBy = isset($allowedSort[$sortCol]) ? "ORDER BY {$allowedSort[$sortCol]} $sortDir" : "ORDER BY m.id_mitra ASC";

        $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM mitra"))['c'];
        $rows = mysqli_fetch_all(mysqli_query($conn, "
            SELECT m.id_mitra, m.nama_mitra, COUNT(mu.id_umkm) as jumlah_umkm
            FROM mitra m
            LEFT JOIN mitra_umkm mu ON m.id_mitra = mu.id_mitra
            GROUP BY m.id_mitra
            $where $orderBy LIMIT $perPage OFFSET $offset
        "), MYSQLI_ASSOC);

    } else {
        echo json_encode(['success' => false, 'message' => 'Tabel tidak valid.']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'rows' => $rows,
        'total' => (int)$total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage),
        'columns' => $columns,
    ]);
}
?>