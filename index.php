<?php session_start(); include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Pemesanan Makanan - Temukan dan pesan makanan favorit Anda dari berbagai mitra pengiriman.">
    <title>Sistem Pemesanan Makanan</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍔</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div id="home-page">

        <div class="home-form-area">
            <!-- title -->
            <div class="home-title" id="home-title">Login</div>

            <!-- Tab -->
            <div class="auth-tabs">
                <button class="auth-tab active" id="tab-login" onclick="switchTab('login')">Login</button>
                <button class="auth-tab" id="tab-register" onclick="switchTab('register')">Register</button>
            </div>

            <!-- LOGIN CEES -->
            <form id="form-login" class="auth-form active" onsubmit="event.preventDefault();">
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">👤</span>
                        <input id="login-username" class="form-input" type="text" placeholder="Username"
                            autocomplete="username" aria-label="Username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">🔑</span>
                        <input id="login-password" class="form-input" type="password" placeholder="Password"
                            autocomplete="current-password" aria-label="Password">
                    </div>
                </div>
                <div id="login-error" class="form-error">Username atau password salah.</div>
                <button class="auth-btn" type="button" onclick="doLogin()">Login</button>
            </form>

            
            <!-- DAFTARRRRRRRRRRRRRRRRRRRRRRRRRR -->
            <form id="form-register" class="auth-form" onsubmit="event.preventDefault();" autocomplete="off">
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">👤</span>
                        <input id="reg-name" class="form-input" type="text" placeholder="Nama Lengkap" aria-label="Nama Lengkap">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">📌</span>
                        <input id="reg-username" class="form-input" type="text" placeholder="Username" aria-label="Username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">🔑</span>
                        <input id="reg-password" class="form-input" type="password" placeholder="Password" aria-label="Password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">📱</span>
                        <input id="reg-phone" class="form-input" type="tel" placeholder="08xxxxxxxxxx" aria-label="Nomor Telepon">
                    </div>
                </div>
                <div id="reg-error" class="form-error">Mohon isi semua field.</div>
                <button class="auth-btn" type="button" onclick="doRegister()">Daftar</button>
            </form>
        </div>

        <!-- awas ombak -->
        <div class="home-wave">
            <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#5C4E40"
                    d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </div>


    <div id="app-page">

        <!-- SIDEBAR  -->
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="sidebar-avatar" id="sidebar-avatar">U</div>
                <div class="sidebar-username" id="sidebar-username">NganuNganuan</div>
                <div class="sidebar-role" id="sidebar-role"></div>
            </div>
            <nav class="sidebar-nav">
                <!-- admin panel -->
                <div class="sidebar-section-label admin-only">Admin Panel</div>
                <div class="nav-item admin-only active" id="nav-admin-dashboard" onclick="navigate('admin-dashboard')">
                    <i class="fas fa-chart-bar nav-icon-fa"></i>Dashboard
                </div>
                <div class="nav-item admin-only" id="nav-data-produk" onclick="navigate('data-produk')">
                    <i class="fas fa-utensils nav-icon-fa"></i>Data Produk
                </div>
                <div class="nav-item admin-only" id="nav-data-umkm" onclick="navigate('data-umkm')">
                    <i class="fas fa-store nav-icon-fa"></i>Data UMKM
                </div>
                <div class="nav-item admin-only" id="nav-data-mitra" onclick="navigate('data-mitra')">
                    <i class="fas fa-handshake nav-icon-fa"></i>Data Mitra
                </div>
                <div class="nav-item admin-only" id="nav-import" onclick="navigate('import')">
                    <i class="fas fa-file-import nav-icon-fa"></i>Bulk Import
                </div>

                <div class="sidebar-section-label admin-only" style="margin-top:0.5rem">Toko & Belanja</div>

                <!-- menu -->
                <div class="nav-item" id="nav-dashboard" onclick="navigate('dashboard')">
                    <i class="fas fa-home nav-icon-fa"></i>Dashboard
                </div>
                <div class="nav-item" id="nav-mitra" onclick="navigate('mitra')">
                    <i class="fas fa-handshake nav-icon-fa"></i>Mitra
                </div>
                <div class="nav-item" id="nav-list-toko" onclick="navigate('list-toko')">
                    <i class="fas fa-store nav-icon-fa"></i>List Toko
                </div>
                <div class="nav-item" id="nav-compare" onclick="navigate('compare')">
                    <i class="fas fa-search-dollar nav-icon-fa"></i>Bandingkan Harga
                </div>
                <div class="nav-item" id="nav-cart" onclick="navigate('cart')">
                    <i class="fas fa-shopping-cart nav-icon-fa"></i>Keranjang
                    <span class="nav-badge" id="cart-badge">0</span>
                </div>
                <div class="nav-item" id="nav-orders" onclick="navigate('orders')">
                    <i class="fas fa-receipt nav-icon-fa"></i>Riwayat Pesanan
                </div>
            </nav>
            <div class="sidebar-logout" onclick="doLogout()">
                <i class="fas fa-sign-out-alt logout-icon-fa"></i>Logout
            </div>
        </aside>

        <!-- billboard -->
        <header class="billboard">
            <div class="billboard-track">
                <span class="billboard-text">Hai sahabat! &nbsp;&nbsp;✦&nbsp;&nbsp; Gaji itu ibarat mantan, cuma lewat
                    doang. jangan lupa makan ya bub &nbsp;&nbsp;✦&nbsp;&nbsp; jangan rindu. ini berat kau takkan kuat.
                    biar aa aja &nbsp;&nbsp;✦&nbsp;&nbsp;
                    PROMO HARI INI: DISKON 50% UNTUK SEMUA TOKO YANG BERMITRA, AYO BELANJAAA &nbsp;&nbsp;✦&nbsp;&nbsp;
                    Kalau liat makanan jangan diliat
                    liat mas nanti keliatan :D &nbsp;&nbsp;✦&nbsp;&nbsp;</span>
            </div>
        </header>

        <!-- main nya -->
        <main class="main-content">

            <!-- ADMIN DASHBOARD (admin doang btw) -->
            <div id="view-admin-dashboard" class="view">
                <h2 class="section-title">📊 Statistik Dashboard</h2>
                <div class="stats-cards" id="stats-cards">
                    <div class="stat-card stat-card-produk">
                        <div class="stat-icon">🍽️</div>
                        <div class="stat-info">
                            <div class="stat-label">Total Produk</div>
                            <div class="stat-value" id="stat-produk">0</div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-umkm">
                        <div class="stat-icon">🏪</div>
                        <div class="stat-info">
                            <div class="stat-label">Total UMKM</div>
                            <div class="stat-value" id="stat-umkm">0</div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-mitra">
                        <div class="stat-icon">🤝</div>
                        <div class="stat-info">
                            <div class="stat-label">Total Mitra</div>
                            <div class="stat-value" id="stat-mitra">0</div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-orders">
                        <div class="stat-icon">📦</div>
                        <div class="stat-info">
                            <div class="stat-label">Total Pesanan</div>
                            <div class="stat-value" id="stat-orders">0</div>
                        </div>
                    </div>
                </div>

                <div class="admin-chart-container">
                    <h3 class="admin-chart-title">Jumlah Produk per UMKM (Top 10)</h3>
                    <div class="admin-chart" id="admin-chart"></div>
                </div>

                <div class="admin-chart-container" style="margin-top:1.5rem;">
                    <h3 class="admin-chart-title">Produk per Jenis</h3>
                    <div class="admin-jenis-chart" id="admin-jenis-chart"></div>
                </div>
            </div>

            <!-- DATA PRODUK TABLE (admin lagi) -->
            <div id="view-data-produk" class="view">
                <div class="dt-header">
                    <h2 class="section-title">Table Produk</h2>
                    <button class="crud-add-btn" onclick="openFoodModal()">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
                <div class="dt-controls">
                    <div class="dt-per-page">
                        <select id="dt-produk-perpage" onchange="dtChangePerPage('produk')">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries per page</span>
                    </div>
                    <div class="dt-search">
                        <span>Search:</span>
                        <input type="text" id="dt-produk-search" oninput="dtSearch('produk')" placeholder="Cari...">
                    </div>
                </div>
                <div class="dt-table-wrap">
                    <table class="dt-table" id="dt-table-produk">
                        <thead><tr></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="dt-footer" id="dt-footer-produk"></div>
            </div>

            <!-- DATA UMKM TABLE (admin) -->
            <div id="view-data-umkm" class="view">
                <div class="dt-header">
                    <h2 class="section-title">Table UMKM</h2>
                    <button class="crud-add-btn" onclick="openStoreModal()">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
                <div class="dt-controls">
                    <div class="dt-per-page">
                        <select id="dt-umkm-perpage" onchange="dtChangePerPage('umkm')">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries per page</span>
                    </div>
                    <div class="dt-search">
                        <span>Search:</span>
                        <input type="text" id="dt-umkm-search" oninput="dtSearch('umkm')" placeholder="Cari...">
                    </div>
                </div>
                <div class="dt-table-wrap">
                    <table class="dt-table" id="dt-table-umkm">
                        <thead><tr></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="dt-footer" id="dt-footer-umkm"></div>
            </div>

            <!-- DATA MITRA TABLE (admin) -->
            <div id="view-data-mitra" class="view">
                <div class="dt-header">
                    <h2 class="section-title">Table Mitra</h2>
                    <button class="crud-add-btn" onclick="openMitraModal()">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
                <div class="dt-controls">
                    <div class="dt-per-page">
                        <select id="dt-mitra-perpage" onchange="dtChangePerPage('mitra')">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries per page</span>
                    </div>
                    <div class="dt-search">
                        <span>Search:</span>
                        <input type="text" id="dt-mitra-search" oninput="dtSearch('mitra')" placeholder="Cari...">
                    </div>
                </div>
                <div class="dt-table-wrap">
                    <table class="dt-table" id="dt-table-mitra">
                        <thead><tr></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="dt-footer" id="dt-footer-mitra"></div>
            </div>


            <!-- DASHBOARD -->
            <div id="view-dashboard" class="view active">
                <div class="promo-container">
                    <div class="promo-header">PROMO</div>
                    <div class="promo-slider-wrapper">
                        <div class="promo-slider" id="promo-slider">
                        </div>
                    </div>
                </div>
                <div class="section-header">
                    <h2 class="section-title">Daftar Toko</h2>
                    <button class="crud-add-btn" onclick="openStoreModal()">
                        <i class="fas fa-plus"></i> Tambah Toko
                    </button>
                </div>
                <div class="store-list" id="dashboard-list"></div>
            </div>

            <!-- LIST TOKO -->
            <div id="view-list-toko" class="view">
                <div class="section-header">
                    <h2 class="section-title" id="list-toko-title">Semua Toko</h2>
                    <button class="crud-add-btn" onclick="openStoreModal()">
                        <i class="fas fa-plus"></i> Tambah Toko
                    </button>
                </div>
                <div class="toko-grid" id="toko-grid"></div>
            </div>

            <!-- MITRA -->
            <div id="view-mitra" class="view">
                <div class="section-header">
                    <h2 class="section-title">Daftar Mitra</h2>
                    <button class="crud-add-btn" onclick="openMitraModal()">
                        <i class="fas fa-plus"></i> Tambah Mitra
                    </button>
                </div>
                <div class="mitra-grid" id="mitra-grid"></div>
            </div>

            <!-- TOKO PER MITRA -->
            <div id="view-toko-mitra" class="view">
                <div class="mitra-filter-label">
                    <div class="mitra-filter-pill" id="toko-mitra-label">Nama Mitra</div>
                </div>
                <div class="toko-grid" id="toko-mitra-grid"></div>
            </div>

            <!-- CATALOG -->
            <div id="view-catalog" class="view">
                <button class="back-btn" aria-label="Kembali" onclick="goBack()">&#8592; Kembali</button>
                <div class="section-header">
                    <div>
                        <div class="catalog-store-title" id="catalog-store-title">Nama Toko</div>
                        <div class="catalog-store-sub" id="catalog-store-sub">Info toko</div>
                    </div>
                    <button class="crud-add-btn" id="btn-add-food" aria-label="Tambah Menu" onclick="openFoodModal()">
                        <i class="fas fa-plus" aria-hidden="true"></i> Tambah Menu
                    </button>
                </div>
                <div class="compare-chips" id="catalog-filter-chips">
                    <button class="compare-chip active" aria-pressed="true" onclick="filterCatalog('All')">Semua</button>
                    <button class="compare-chip" aria-pressed="false" onclick="filterCatalog('Makanan')">Makanan</button>
                    <button class="compare-chip" aria-pressed="false" onclick="filterCatalog('Minuman')">Minuman</button>
                    <button class="compare-chip" aria-pressed="false" onclick="filterCatalog('Topping')">Topping</button>
                </div>
                <div id="catalog-empty" class="empty-state" style="display:none;">
                    <div class="empty-icon">🍽️</div>
                    <div>Belum ada menu di kategori ini.</div>
                </div>
                <div class="food-grid" id="food-grid"></div>
            </div>

            <!-- keranjang makanan -->
            <div id="view-cart" class="view">
                <h2 class="section-title">Keranjang</h2>
                <div id="cart-empty" style="text-align:center;padding:3rem 0;color:#999;display:none;">
                    <div style="font-size:3rem;margin-bottom:.5rem;">🛒</div>
                    Keranjang masih kosong
                </div>
                <div class="cart-layout" id="cart-layout">
                    <div class="cart-panel">
                        <div class="cart-panel-title">Pesanan anda</div>
                        <div id="cart-items"></div>
                    </div>
                    <div class="summary-panel">
                        <div id="cart-summary"></div>
                        <button class="pay-btn" onclick="startPayment()">Bayar</button>
                    </div>
                </div>
            </div>

            <!-- riwayat pesanan -->
            <div id="view-orders" class="view">
                <h2 class="section-title">Riwayat Pesanan</h2>
                <div id="orders-list"></div>
            </div>

            <!-- COMPARE HARGAAAAA -->
            <div id="view-compare" class="view">
                <h2 class="section-title">Bandingkan Harga Makanan</h2>
                <div class="compare-search-wrap">
                    <i class="fas fa-search compare-search-icon"></i>
                    <input type="text" class="compare-search-input" id="compare-search" placeholder="Cari makanan (misal: nasi, bakso, sate, mie)..." oninput="onCompareSearchInput()">
                </div>
                <div class="compare-chips">
                    <?php
                    $common_keywords = ['Nasi', 'Bakso', 'Mie', 'Sate', 'Martabak', 'Kebab', 'Cireng', 'Kopi', 'Teh', 'Es', 'Cilor', 'Ketoprak', 'Soto', 'Lontong', 'Bola Ubi'];
                    $emoji_map = [
                        'Nasi' => '🍚',
                        'Bakso' => '🍜',
                        'Mie' => '🍝',
                        'Sate' => '🍢',
                        'Martabak' => '🥞',
                        'Kebab' => '🌯',
                        'Cireng' => '🍘',
                        'Kopi' => '☕',
                        'Teh' => '🍵',
                        'Es' => '🍨',
                        'Cilor' => '🍢',
                        'Ketoprak' => '🍲',
                        'Soto' => '🥣',
                        'Lontong' => '🍛',
                        'Bola Ubi' => '🍠'
                    ];
                    foreach ($common_keywords as $keyword) {
                        $q = mysqli_query($conn, "SELECT COUNT(*) as count FROM produk WHERE nama_produk LIKE '%$keyword%'");
                        if ($q) {
                            $row = mysqli_fetch_assoc($q);
                            if ($row['count'] > 0) {
                                $emoji = isset($emoji_map[$keyword]) ? $emoji_map[$keyword] : '🍽️';
                                echo '<span class="compare-chip" onclick="searchCompareChip(\'' . $keyword . '\')">' . $emoji . ' ' . $keyword . '</span>';
                            }
                        }
                    }
                    ?>
                </div>
                <div id="compare-empty" style="text-align:center;padding:3rem 0;color:#7a6a5e;">
                    <div style="font-size:3rem;margin-bottom:.5rem;">🔍</div>
                    Ketik nama makanan di atas atau pilih kategori cepat untuk membandingkan harga antar-mitra
                </div>
                <div class="compare-results" id="compare-results" style="display:none;"></div>
            </div>

            <!-- BULK IMPORT, fitur terkeren  -->
            <div id="view-import" class="view">
                <h2 class="section-title">Bulk Import Toko & Makanan</h2>
                <div style="background:var(--summary-bg); border: 2px solid var(--brown); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.85rem; line-height: 1.5;">
                    <strong>Petunjuk Format Input:</strong><br>
                    1. Salin data dari Excel / Google Sheets, atau ketik baris data terpisah oleh tombol Tab.<br>
                    2. Format kolom: <code>Toko [Tab] Produk [Tab] Harga [Tab] Varian rasa</code> (atau ada tambahan kolom <code>NO</code> di awal, sistem akan mendeteksi otomatis).<br>
                    3. Contoh baris data:<br>
                    <pre style="background:rgba(255,255,255,0.5); padding: 0.5rem; margin-top: 0.5rem; border-radius: 4px; font-size: 0.75rem;">Winmilk	Choco	12000	Choco cheese
Winmilk	Smoothies	12000	Cheesecake</pre>
                </div>
                <div class="import-textarea-wrap">
                    <textarea class="import-textarea" id="import-textarea" placeholder="Tempel data tabel di sini..."></textarea>
                </div>
                <div class="import-btn-group">
                    <button class="crud-btn crud-btn-save" style="max-width: 200px;" onclick="parseBulkImportText()">Proses & Review</button>
                    <button class="crud-btn crud-btn-cancel" style="max-width: 200px;" onclick="document.getElementById('import-textarea').value = ''; document.getElementById('import-preview-container').style.display = 'none';">Bersihkan</button>
                </div>

                <div id="import-preview-container" style="display:none; margin-top: 2rem;">
                    <h3 class="section-title">Preview Data Hasil Parse</h3>
                    <div style="overflow-x: auto;">
                        <table class="import-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Toko</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Varian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="import-preview-body"></tbody>
                        </table>
                    </div>
                    <div class="import-btn-group">
                        <button class="crud-btn crud-btn-save" id="btn-confirm-import" style="max-width: 250px; font-size: 0.65rem;" onclick="submitBulkImport()">Konfirmasi Import ke Database</button>
                    </div>
                </div>
            </div>

        </main>
    </div>


    <div id="modal-qris" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-title">Scan QRIS</div>
            <div class="modal-sub">Gunakan e-wallet atau m-banking untuk scan QR di bawah</div>
            <div class="qr-wrap">
                <div class="qr-inner" id="qr-grid"></div>
            </div>
            <div class="qr-total" id="qris-total">Rp 0</div>
            <button class="modal-btn modal-btn-primary" onclick="simulatePayment()">Simulasi Pembayaran
                Berhasil</button>
            <button class="modal-btn modal-btn-secondary" onclick="closeModal('modal-qris')">Batal</button>
        </div>
    </div>

    <div id="modal-success" class="modal-overlay">
        <div class="modal-box">
            <div class="success-icon">✅</div>
            <div class="modal-title">Pembayaran Berhasil!</div>
            <div class="modal-sub">Terima kasih sudah berbelanja!<br>Pesanan Anda sedang diproses.</div>
            <button class="modal-btn modal-btn-primary" onclick="finishPayment()">Kembali ke dashboard</button>
        </div>
    </div>

    <!-- crud -->

    <!-- Tambah/Edit Toko -->
    <div id="modal-store" class="crud-modal-overlay">
        <div class="crud-modal-box">
            <div class="crud-modal-title" id="modal-store-title">Tambah Toko</div>
            <input type="hidden" id="store-edit-id" value="">
            <div class="crud-form-group">
                <label class="crud-form-label">Nama Toko</label>
                <input type="text" class="crud-form-input" id="store-name" placeholder="Contoh: Warung Sate">
            </div>
            <div class="crud-form-group">
                <label class="crud-form-label">Icon (Emoji)</label>
                <input type="text" class="crud-form-input" id="store-icon" placeholder="🏪" maxlength="4">
                <div class="emoji-hint">Tekan Win + . untuk emoji picker</div>
            </div>
            <div class="crud-form-group">
                <label class="crud-form-label">Deskripsi</label>
                <input type="text" class="crud-form-input" id="store-info" placeholder="Contoh: Sate ayam & kambing terenak">
            </div>
            <div class="crud-form-group">
                <label class="crud-form-label">Mitra (opsional)</label>
                <select class="crud-form-select" id="store-mitra">
                    <option value="">-- Tanpa Mitra --</option>
                </select>
            </div>
            <div class="crud-btn-group">
                <button class="crud-btn crud-btn-cancel" onclick="closeCrudModal('modal-store')">Batal</button>
                <button class="crud-btn crud-btn-save" onclick="saveStore()">Simpan</button>
            </div>
        </div>
    </div>

    <!--  Tambah/Edit Makanan -->
    <div id="modal-food" class="crud-modal-overlay">
        <div class="crud-modal-box">
            <div class="crud-modal-title" id="modal-food-title">Tambah Menu</div>
            <input type="hidden" id="food-edit-id" value="">
            <input type="hidden" id="food-store-id" value="">
            <div class="crud-form-group">
                <label class="crud-form-label">Nama Makanan</label>
                <input type="text" class="crud-form-input" id="food-name" placeholder="Contoh: Nasi Goreng">
            </div>
            <div class="crud-form-group">
                <label class="crud-form-label">Harga (Rp)</label>
                <input type="number" class="crud-form-input" id="food-price" placeholder="25000" min="1">
            </div>
            <div class="crud-form-group">
                <label class="crud-form-label">Jenis</label>
                <select class="crud-form-select" id="food-jenis">
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Topping">Topping</option>
                </select>
            </div>
            <div class="crud-btn-group">
                <button class="crud-btn crud-btn-cancel" onclick="closeCrudModal('modal-food')">Batal</button>
                <button class="crud-btn crud-btn-save" onclick="saveFood()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Tambah/Edit Mitra -->
    <div id="modal-mitra" class="crud-modal-overlay">
        <div class="crud-modal-box">
            <div class="crud-modal-title" id="modal-mitra-title">Tambah Mitra</div>
            <input type="hidden" id="mitra-edit-mode" value="add">
            <div class="crud-form-group" id="mitra-id-group">
                <label class="crud-form-label">ID Mitra</label>
                <input type="text" class="crud-form-input" id="mitra-id" placeholder="Contoh: tokopedia">
                <div class="emoji-hint">Huruf kecil, tanpa spasi. Tidak bisa diubah setelah dibuat.</div>
            </div>
            <div class="crud-form-group">
                <label class="crud-form-label">Nama Mitra</label>
                <input type="text" class="crud-form-input" id="mitra-name" placeholder="Contoh: Tokopedia">
            </div>
            <div class="crud-btn-group">
                <button class="crud-btn crud-btn-cancel" onclick="closeCrudModal('modal-mitra')">Batal</button>
                <button class="crud-btn crud-btn-save" onclick="saveMitra()">Simpan</button>
            </div>
            
        </div>
    </div>

    <!--  Konfirmasi Hapus -->
    <div id="modal-delete" class="crud-modal-overlay">
        <div class="crud-modal-box">
            <div class="delete-icon">🗑️</div>
            <div class="crud-modal-title">Yakin Hapus?</div>
            <div class="delete-confirm-text" id="delete-confirm-text">Apakah kamu yakin ingin menghapus ini?</div>
            <div class="delete-confirm-name" id="delete-confirm-name"></div>
            <div class="crud-btn-group">
                <button class="crud-btn crud-btn-cancel" onclick="closeCrudModal('modal-delete')">Batal</button>
                <button class="crud-btn crud-btn-danger" id="delete-confirm-btn" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toast-container"></div>

    <script>
        /* narik data */
        <?php
        // Ambil mitra dari tabel baru
        $mitras_raw = mysqli_fetch_all(mysqli_query($conn, "SELECT id_mitra, nama_mitra FROM mitra"), MYSQLI_ASSOC);
        $mitras = [];
        foreach ($mitras_raw as $m) {
            $mitras[] = ['id' => (string)$m['id_mitra'], 'name' => $m['nama_mitra']];
        }

        // Ambil umkm - map ke format stores
        $stores_raw = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM umkm"), MYSQLI_ASSOC);
        $stores = [];
        foreach ($stores_raw as $s) {
            $mu = mysqli_query($conn, "SELECT id_mitra FROM mitra_umkm WHERE id_umkm = " . (int)$s['id_umkm'] . " LIMIT 1");
            $mitra_id = null;
            if ($mu && mysqli_num_rows($mu) > 0) {
                $mrow = mysqli_fetch_assoc($mu);
                $mitra_id = (string)$mrow['id_mitra'];
            }
            $stores[] = [
                'id' => (int)$s['id_umkm'],
                'name' => $s['nama_umkm'],
                'mitra_id' => $mitra_id,
                'icon' => '🏪',
                'info' => $s['alamat'] ?? ''
            ];
        }

        // Ambil produk + join jenis
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

        $promos = [];
        $labels = ['SUPER DISKON', 'PROMO KILAT', 'BEST SELLER', 'MURAH BANGET'];
        $emojis = ['🍔', '🍕', '🍰', '🍜', '🥤', '🍞', '🥞', '🌯', '🍚'];
        $promo_query = mysqli_query($conn, "
            SELECT p.id_produk, p.id_umkm, p.nama_produk, p.harga, j.nama_jenis 
            FROM produk p 
            LEFT JOIN jenis j ON p.id_jenis = j.id_jenis 
            ORDER BY RAND() LIMIT 4
        ");
        if ($promo_query && mysqli_num_rows($promo_query) > 0) {
            $i = 0;
            while ($row = mysqli_fetch_assoc($promo_query)) {
                $old_price = round((int)$row['harga'] * 1.3, -3); // Diskon ~30%
                $icon = '🍽️';
                if ($row['nama_jenis'] == 'Minuman') $icon = '🥤';
                else if (stripos($row['nama_produk'], 'bakso') !== false || stripos($row['nama_produk'], 'mie') !== false) $icon = '🍜';
                else if (stripos($row['nama_produk'], 'nasi') !== false) $icon = '🍚';
                else if (stripos($row['nama_produk'], 'martabak') !== false) $icon = '🥞';
                else $icon = $emojis[array_rand($emojis)];

                $promos[] = [
                    'storeId' => (int)$row['id_umkm'],
                    'foodId' => (int)$row['id_produk'],
                    'name' => $row['nama_produk'],
                    'oldPrice' => $old_price,
                    'newPrice' => (int)$row['harga'],
                    'icon' => $icon,
                    'label' => $labels[$i % count($labels)]
                ];
                $i++;
            }
        } else {
            $promos = [
                ['storeId' => 1, 'foodId' => 1, 'name' => 'Bakpao Ayam', 'oldPrice' => 10000, 'newPrice' => 7000, 'icon' => '🫓', 'label' => 'SUPER DISKON']
            ];
        }
        ?>

        let mitras = <?php echo json_encode($mitras); ?>;
        let stores = <?php echo json_encode($stores); ?>.map(s => ({ ...s, id: parseInt(s.id) }));
        let foods = <?php echo json_encode($foods); ?>;
        
        window.promoProducts = <?php echo json_encode($promos); ?>;
        Object.keys(foods).forEach(k => {
            foods[k] = foods[k].map(f => ({ ...f, id: parseInt(f.id), price: parseInt(f.price) }));
        });

    </script>
    <script src="js/app.js"></script>
</body>
</html>