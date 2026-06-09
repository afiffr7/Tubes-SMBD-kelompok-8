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
            <!-- Dynamic title -->
            <div class="home-title" id="home-title">Login</div>

            <!-- Tabs -->
            <div class="auth-tabs">
                <button class="auth-tab active" id="tab-login" onclick="switchTab('login')">Login</button>
                <button class="auth-tab" id="tab-register" onclick="switchTab('register')">Register</button>
            </div>

            <!-- LOGIN CEES -->
            <form id="form-login" class="auth-form active" onsubmit="event.preventDefault();">
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input id="login-username" class="form-input" type="text" placeholder="Username"
                            autocomplete="username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon">🔑</span>
                        <input id="login-password" class="form-input" type="password" placeholder="Password"
                            autocomplete="current-password">
                    </div>
                </div>
                <div id="login-error" class="form-error">Username atau password salah.</div>
                <button class="auth-btn" type="button" onclick="doLogin()">Login</button>
            </form>

            <!-- DAFTARRRRRRRRRRRRRRRRRRRRRRRRRR -->
            <form id="form-register" class="auth-form" onsubmit="event.preventDefault();" autocomplete="off">
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input id="reg-name" class="form-input" type="text" placeholder="Nama Lengkap">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon">📌</span>
                        <input id="reg-username" class="form-input" type="text" placeholder="Username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon">🔑</span>
                        <input id="reg-password" class="form-input" type="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrap">
                        <span class="input-icon">📱</span>
                        <input id="reg-phone" class="form-input" type="tel" placeholder="08xxxxxxxxxx">
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
            </div>
            <nav class="sidebar-nav">
                <div class="nav-item active" id="nav-dashboard" onclick="navigate('dashboard')">
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
                <div class="nav-item" id="nav-import" onclick="navigate('import')">
                    <i class="fas fa-file-import nav-icon-fa"></i>Bulk Import
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

        <!-- running billboard -->
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
                <button class="back-btn" onclick="goBack()">&#8592; Kembali</button>
                <div class="section-header">
                    <div>
                        <div class="catalog-store-title" id="catalog-store-title">Nama Toko</div>
                        <div class="catalog-store-sub" id="catalog-store-sub">Info toko</div>
                    </div>
                    <button class="crud-add-btn" id="btn-add-food" onclick="openFoodModal()">
                        <i class="fas fa-plus"></i> Tambah Menu
                    </button>
                </div>
                <div class="food-grid" id="food-grid"></div>
            </div>

            <!-- CART -->
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

            <!-- ORDERS -->
            <div id="view-orders" class="view">
                <h2 class="section-title">Riwayat Pesanan</h2>
                <div id="orders-list"></div>
            </div>

            <!-- COMPARE PRICE -->
            <div id="view-compare" class="view">
                <h2 class="section-title">Bandingkan Harga Makanan</h2>
                <div class="compare-search-wrap">
                    <i class="fas fa-search compare-search-icon"></i>
                    <input type="text" class="compare-search-input" id="compare-search" placeholder="Cari makanan (misal: nasi, burger, sushi)..." oninput="onCompareSearchInput()">
                </div>
                <div class="compare-chips">
                    <span class="compare-chip" onclick="searchCompareChip('Nasi')">🍚 Nasi</span>
                    <span class="compare-chip" onclick="searchCompareChip('Burger')">🍔 Burger</span>
                    <span class="compare-chip" onclick="searchCompareChip('Sushi')">🍣 Sushi</span>
                    <span class="compare-chip" onclick="searchCompareChip('Pizza')">🍕 Pizza</span>
                    <span class="compare-chip" onclick="searchCompareChip('Bakso')">🍜 Bakso</span>
                    <span class="compare-chip" onclick="searchCompareChip('Kopi')">☕ Kopi</span>
                </div>
                <div id="compare-empty" style="text-align:center;padding:3rem 0;color:#7a6a5e;">
                    <div style="font-size:3rem;margin-bottom:.5rem;">🔍</div>
                    Ketik nama makanan di atas atau pilih kategori cepat untuk membandingkan harga antar-mitra
                </div>
                <div class="compare-results" id="compare-results" style="display:none;"></div>
            </div>

            <!-- BULK IMPORT -->
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

    <!-- CRUD MODALS -->

    <!-- Modal: Tambah/Edit Toko -->
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

    <!-- Modal: Tambah/Edit Makanan -->
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
            <div class="crud-btn-group">
                <button class="crud-btn crud-btn-cancel" onclick="closeCrudModal('modal-food')">Batal</button>
                <button class="crud-btn crud-btn-save" onclick="saveFood()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal: Tambah/Edit Mitra -->
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

    <!-- Modal: Konfirmasi Hapus -->
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

    <!-- Toast container -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        /* data fetch */
        <?php
        $mitras_q = mysqli_query($conn, "SELECT * FROM mitras");
        $stores_q = mysqli_query($conn, "SELECT * FROM stores");
        $foods_q = mysqli_query($conn, "SELECT * FROM foods");

        $mitras = mysqli_fetch_all($mitras_q, MYSQLI_ASSOC);
        $stores = mysqli_fetch_all($stores_q, MYSQLI_ASSOC);
        $foods_raw = mysqli_fetch_all($foods_q, MYSQLI_ASSOC);

        $foods = [];
        foreach ($foods_raw as $f) {
            $foods[$f['store_id']][] = [
                'id' => (int)$f['id'],
                'name' => $f['name'],
                'price' => (int)$f['price']
            ];
        }
        ?>

        let mitras = <?php echo json_encode($mitras); ?>;
        let stores = <?php echo json_encode($stores); ?>.map(s => ({ ...s, id: parseInt(s.id) }));
        let foods = <?php echo json_encode($foods); ?>;

        // Normalize food prices to int
        Object.keys(foods).forEach(k => {
            foods[k] = foods[k].map(f => ({ ...f, id: parseInt(f.id), price: parseInt(f.price) }));
        });

    </script>
    <script src="js/app.js"></script>
</body>
</html>


