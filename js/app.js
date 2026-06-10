const promoProducts = [
    { storeId: 1, foodId: 101, name: 'Nasi Rendang', oldPrice: 30000, newPrice: 20000, icon: '🍔', label: 'SUPER DISKON' },
    { storeId: 2, foodId: 201, name: 'Beef Burger', oldPrice: 40000, newPrice: 25000, icon: '🍔', label: 'PROMO KILAT' },
    { storeId: 4, foodId: 401, name: 'Pepperoni Pizza', oldPrice: 75000, newPrice: 50000, icon: '🍕', label: 'BEST SELLER' },
    { storeId: 6, foodId: 602, name: 'Mie Ayam', oldPrice: 22000, newPrice: 15000, icon: '🍜', label: 'MURAH BANGET' },
];

let cart = [];
let currentView = 'dashboard';
let prevView = 'dashboard';
let currentMitra = null;
let currentStoreId = null;
let loggedUser = null;
let promoIndex = 0;
let promoInterval = null;
let pendingDelete = null; // { type: 'store'|'food'|'mitra', id name }

/* FORMAT */
const fmt = n => 'Rp ' + n.toLocaleString('id-ID');

/* XSS SANITIZER — neutralize HTML tags in user-generated text */
function escHTML(str) {
    return (str || '').replace(/[&<>'"]/g, tag => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
    }[tag]));
}

/* TOAST */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => { if (toast.parentNode) toast.remove(); }, 3200);
}

/* CRUD MODAL HELPERS */
function closeCrudModal(id) {
    document.getElementById(id).classList.remove('active');
}

/* REFRESH DATA FROM SERVER */
async function refreshData() {
    try {
        const res = await fetch('api.php?action=get_data');
        const data = await res.json();
        mitras = data.mitras;
        stores = data.stores.map(s => ({ ...s, id: parseInt(s.id) }));
        foods = {};
        Object.keys(data.foods).forEach(k => {
            foods[k] = data.foods[k].map(f => ({ ...f, id: parseInt(f.id), price: parseInt(f.price) }));
        });
    } catch (e) {
        console.error('Gagal refresh data:', e);
    }
}

/* ===== STORE CRUD ===== */

function openStoreModal(storeId = null) {
    const title = document.getElementById('modal-store-title');
    const editId = document.getElementById('store-edit-id');
    const nameEl = document.getElementById('store-name');
    const iconEl = document.getElementById('store-icon');
    const infoEl = document.getElementById('store-info');
    const mitraEl = document.getElementById('store-mitra');

    // Populate mitra dropdown
    mitraEl.innerHTML = '<option value="">-- Tanpa Mitra --</option>' +
        mitras.map(m => `<option value="${escHTML(m.id)}">${escHTML(m.name)}</option>`).join('');

    if (storeId) {
        const store = stores.find(s => s.id === storeId);
        if (!store) return;
        title.textContent = 'Edit Toko';
        editId.value = storeId;
        nameEl.value = store.name;
        iconEl.value = store.icon || '';
        infoEl.value = store.info || '';
        mitraEl.value = store.mitra_id || '';
    } else {
        title.textContent = 'Tambah Toko';
        editId.value = '';
        nameEl.value = '';
        iconEl.value = '';
        infoEl.value = '';
        mitraEl.value = '';
    }

    document.getElementById('modal-store').classList.add('active');
}

async function saveStore() {
    const editId = document.getElementById('store-edit-id').value;
    const name = document.getElementById('store-name').value.trim();
    const icon = document.getElementById('store-icon').value.trim() || '🏪';
    const info = document.getElementById('store-info').value.trim();
    const mitra_id = document.getElementById('store-mitra').value;

    if (!name) {
        showToast('Nama toko wajib diisi!', 'error');
        return;
    }

    const action = editId ? 'update_store' : 'add_store';
    const body = { name, icon, info, mitra_id };
    if (editId) body.id = parseInt(editId);

    try {
        const res = await fetch(`api.php?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message);
            closeCrudModal('modal-store');
            await refreshData();
            // Re-render current view
            if (currentView === 'dashboard') renderDashboard();
            else if (currentView === 'list-toko') renderListToko();
            else if (currentView === 'toko-mitra' && currentMitra) renderTokoMitra(currentMitra);
        } else {
            showToast(data.message, 'error');
        }
    } catch (e) {
        showToast('Gagal menyimpan toko.', 'error');
    }
}

/* ===== FOOD CRUD ===== */

function openFoodModal(foodId = null) {
    const title = document.getElementById('modal-food-title');
    const editId = document.getElementById('food-edit-id');
    const storeIdEl = document.getElementById('food-store-id');
    const nameEl = document.getElementById('food-name');
    const priceEl = document.getElementById('food-price');
    const jenisEl = document.getElementById('food-jenis');

    storeIdEl.value = currentStoreId || '';

    if (foodId) {
        const foodList = foods[currentStoreId] || [];
        const food = foodList.find(f => f.id === foodId);
        if (!food) return;
        title.textContent = 'Edit Menu';
        editId.value = foodId;
        nameEl.value = food.name;
        priceEl.value = food.price;
        jenisEl.value = food.jenis || 'Makanan';
    } else {
        title.textContent = 'Tambah Menu';
        editId.value = '';
        nameEl.value = '';
        priceEl.value = '';
        jenisEl.value = 'Makanan';
    }

    document.getElementById('modal-food').classList.add('active');
}

async function saveFood() {
    const editId = document.getElementById('food-edit-id').value;
    const store_id = parseInt(document.getElementById('food-store-id').value);
    const name = document.getElementById('food-name').value.trim();
    const price = parseInt(document.getElementById('food-price').value);

    if (!name || !price || price <= 0) {
        showToast('Nama dan harga wajib diisi!', 'error');
        return;
    }

    const action = editId ? 'update_food' : 'add_food';
    const jenis = document.getElementById('food-jenis').value;
    const body = { name, price, jenis };
    if (editId) body.id = parseInt(editId);
    else body.store_id = store_id;

    try {
        const res = await fetch(`api.php?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message);
            closeCrudModal('modal-food');
            await refreshData();
            renderCatalog(currentStoreId);
        } else {
            showToast(data.message, 'error');
        }
    } catch (e) {
        showToast('Gagal menyimpan menu.', 'error');
    }
}

/* ===== MITRA CRUD ===== */

function openMitraModal(mitraId = null) {
    const title = document.getElementById('modal-mitra-title');
    const editMode = document.getElementById('mitra-edit-mode');
    const idEl = document.getElementById('mitra-id');
    const nameEl = document.getElementById('mitra-name');
    const idGroup = document.getElementById('mitra-id-group');

    if (mitraId) {
        const mitra = mitras.find(m => m.id === mitraId);
        if (!mitra) return;
        title.textContent = 'Edit Mitra';
        editMode.value = 'edit';
        idEl.value = mitra.id;
        idEl.readOnly = true;
        idGroup.querySelector('.emoji-hint').style.display = 'none';
        nameEl.value = mitra.name;
    } else {
        title.textContent = 'Tambah Mitra';
        editMode.value = 'add';
        idEl.value = '';
        idEl.readOnly = false;
        idGroup.querySelector('.emoji-hint').style.display = 'block';
        nameEl.value = '';
    }

    document.getElementById('modal-mitra').classList.add('active');
}

async function saveMitra() {
    const editMode = document.getElementById('mitra-edit-mode').value;
    const id = document.getElementById('mitra-id').value.trim();
    const name = document.getElementById('mitra-name').value.trim();

    if (!id || !name) {
        showToast('ID dan nama mitra wajib diisi!', 'error');
        return;
    }

    const action = editMode === 'edit' ? 'update_mitra' : 'add_mitra';

    try {
        const res = await fetch(`api.php?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, name })
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message);
            closeCrudModal('modal-mitra');
            await refreshData();
            renderMitra();
        } else {
            showToast(data.message, 'error');
        }
    } catch (e) {
        showToast('Gagal menyimpan mitra.', 'error');
    }
}

/* ===== DELETE CRUD ===== */

function openDeleteModal(type, id, name) {
    pendingDelete = { type, id, name };

    let text = 'Apakah kamu yakin ingin menghapus ini?';
    if (type === 'store') text = 'Toko dan semua menunya akan dihapus permanen!';
    if (type === 'mitra') text = 'Mitra ini akan dihapus permanen.';
    if (type === 'food') text = 'Menu ini akan dihapus dari toko.';

    document.getElementById('delete-confirm-text').textContent = text;
    document.getElementById('delete-confirm-name').textContent = name;
    document.getElementById('modal-delete').classList.add('active');
}

async function confirmDelete() {
    if (!pendingDelete) return;
    const { type, id } = pendingDelete;

    const actionMap = {
        'store': 'delete_store',
        'food': 'delete_food',
        'mitra': 'delete_mitra'
    };

    try {
        const res = await fetch(`api.php?action=${actionMap[type]}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message);
            closeCrudModal('modal-delete');
            await refreshData();

            if (type === 'store') {
                if (currentView === 'dashboard') renderDashboard();
                else if (currentView === 'list-toko') renderListToko();
                else if (currentView === 'toko-mitra' && currentMitra) renderTokoMitra(currentMitra);
            } else if (type === 'food') {
                renderCatalog(currentStoreId);
            } else if (type === 'mitra') {
                renderMitra();
            }
        } else {
            showToast(data.message, 'error');
        }
    } catch (e) {
        showToast('Gagal menghapus.', 'error');
    }

    pendingDelete = null;
}

/* TAB SWITCH DIANTARA  */
function switchTab(tab) {
    document.getElementById('home-title').textContent = tab === 'login' ? 'Login' : 'Register';
    document.getElementById('form-login').classList.toggle('active', tab === 'login');
    document.getElementById('form-register').classList.toggle('active', tab === 'register');
    document.getElementById('tab-login').classList.toggle('active', tab === 'login');
    document.getElementById('tab-register').classList.toggle('active', tab === 'register');
    document.getElementById('login-error').classList.remove('visible');
    document.getElementById('reg-error').classList.remove('visible');
}

/* AUTH */
async function doLogin() {
    const username = document.getElementById('login-username').value.trim();
    const password = document.getElementById('login-password').value;
    const errEl = document.getElementById('login-error');

    if (!username || !password) {
        errEl.textContent = 'Mohon isi username dan password.';
        errEl.classList.add('visible');
        return;
    }

    const res = await fetch('api.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    });
    const data = await res.json();

    if (data.success) {
        loggedUser = data.user;
        errEl.classList.remove('visible');
        document.getElementById('login-username').value = '';
        document.getElementById('login-password').value = '';
        enterApp(loggedUser);
    } else {
        errEl.textContent = data.message;
        errEl.classList.add('visible');
    }
}

async function doRegister() {
    const name = document.getElementById('reg-name').value.trim();
    const username = document.getElementById('reg-username').value.trim();
    const password = document.getElementById('reg-password').value;
    const phone = document.getElementById('reg-phone').value.trim();
    const errEl = document.getElementById('reg-error');

    if (!name || !username || !password || !phone) {
        errEl.textContent = 'Mohon isi semua field.';
        errEl.classList.add('visible');
        return;
    }

    const res = await fetch('api.php?action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, username, password, phone })
    });
    const data = await res.json();

    if (data.success) {
        loggedUser = data.user;
        errEl.classList.remove('visible');
        ['reg-name', 'reg-username', 'reg-password', 'reg-phone'].forEach(id => document.getElementById(id).value = '');
        enterApp(loggedUser);
    } else {
        errEl.textContent = data.message;
        errEl.classList.add('visible');
    }
}

function enterApp(user) {
    // Hide home, show app
    document.getElementById('home-page').style.display = 'none';
    document.getElementById('app-page').style.display = 'flex';

    // Set username in sidebar
    const initials = user.name ? user.name.charAt(0).toUpperCase() : 'U';
    document.getElementById('sidebar-avatar').textContent = initials;
    document.getElementById('sidebar-username').textContent = user.username || user.name;

    // Set role class on body
    document.body.classList.remove('role-admin', 'role-user');
    if (user.role === 'admin') {
        document.body.classList.add('role-admin');
    } else {
        document.body.classList.add('role-user');
    }

    navigate('dashboard');
}

async function doLogout() {
    // Destroy server session
    try {
        await fetch('api.php?action=logout', { method: 'POST' });
    } catch (e) {
        console.error('Logout session error:', e);
    }

    loggedUser = null;
    cart = [];
    updateBadge();

    document.getElementById('app-page').style.display = 'none';
    document.getElementById('home-page').style.display = 'flex';

    // Clear role class from body
    document.body.classList.remove('role-admin', 'role-user');

    // Reset tab to login
    switchTab('login');
}

function navigate(view, data) {
    // Stop promo slider if keluar dashboard
    if (view !== 'dashboard' && promoInterval) {
        clearInterval(promoInterval);
    }

    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));

    // hl dashboard
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    const navMap = {
        'dashboard': 'nav-dashboard',
        'mitra': 'nav-mitra',
        'list-toko': 'nav-list-toko',
        'compare': 'nav-compare',
        'import': 'nav-import',
        'toko-mitra': 'nav-mitra',
        'catalog': 'nav-list-toko',
        'cart': 'nav-cart',
        'orders': 'nav-orders',
    };
    if (navMap[view]) document.getElementById(navMap[view]).classList.add('active');

    if (view !== 'catalog') prevView = view;
    currentView = view;

    document.getElementById('view-' + view).classList.add('active');

    // Render content
    if (view === 'dashboard') renderDashboard();
    if (view === 'list-toko') renderListToko();
    if (view === 'mitra') renderMitra();
    if (view === 'toko-mitra') renderTokoMitra(data);
    if (view === 'catalog') renderCatalog(data);
    if (view === 'cart') renderCart();
    if (view === 'compare') renderCompare();
    if (view === 'import') renderImport();
    if (view === 'orders') renderOrders();
}

function goBack() { navigate(prevView, currentMitra); }

/* Helper: escape single quotes for onclick attributes */
function escAttr(str) {
    return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

/* ===== PRICE COMPARISON ===== */
function performCompareSearch(query) {
    query = query.toLowerCase().trim();
    const resultsContainer = document.getElementById('compare-results');
    const emptyContainer = document.getElementById('compare-empty');

    if (query.length === 0) {
        resultsContainer.style.display = 'none';
        emptyContainer.style.display = 'block';
        emptyContainer.innerHTML = `
                    <div style="font-size:3rem;margin-bottom:.5rem;">🔍</div>
                    Ketik nama makanan di atas atau pilih kategori cepat untuk membandingkan harga antar-mitra
                `;
        return;
    }

    // Cari makanan di semua toko
    let matches = [];
    Object.keys(foods).forEach(storeIdStr => {
        const storeId = parseInt(storeIdStr);
        const store = stores.find(s => s.id === storeId);
        if (!store) return;

        const storeFoods = foods[storeIdStr] || [];
        const mitra = mitras.find(m => m.id === store.mitra_id);

        storeFoods.forEach(f => {
            if (f.name.toLowerCase().includes(query) || store.name.toLowerCase().includes(query)) {
                matches.push({
                    id: f.id,
                    name: f.name,
                    price: f.price,
                    store: store,
                    mitra: mitra
                });
            }
        });
    });

    // Urutkan harga termurah ke termahal
    matches.sort((a, b) => a.price - b.price);

    if (matches.length === 0) {
        resultsContainer.style.display = 'none';
        emptyContainer.style.display = 'block';
        emptyContainer.innerHTML = `
                    <div style="font-size:3rem;margin-bottom:.5rem;">😢</div>
                    Tidak ada makanan yang cocok dengan pencarian "${escHTML(query)}"
                `;
        return;
    }

    emptyContainer.style.display = 'none';
    resultsContainer.style.display = 'flex';

    resultsContainer.innerHTML = matches.map(m => {
        let badgeClass = 'badge-unknown';
        let mitraName = 'Toko Mandiri';
        if (m.mitra) {
            mitraName = m.mitra.name;
            if (m.mitra.id === 'gojek') badgeClass = 'badge-gojek';
            else if (m.mitra.id === 'grab') badgeClass = 'badge-grab';
            else if (m.mitra.id === 'shopee') badgeClass = 'badge-shopee';
        }

        return `
                    <div class="compare-item-card">
                        <div class="compare-item-left">
                            <div class="compare-store-icon">${escHTML(m.store.icon) || '🏪'}</div>
                            <div class="compare-food-details">
                                <div class="compare-food-title">${escHTML(m.name)}</div>
                                <div class="compare-store-meta">
                                    <span>di <strong>${escHTML(m.store.name)}</strong></span>
                                    <span class="mitra-badge ${badgeClass}">${escHTML(mitraName)}</span>
                                </div>
                            </div>
                        </div>
                        <div class="compare-item-right">
                            <div class="compare-price">${fmt(m.price)}</div>
                            <button class="compare-add-btn" onclick="addToCart(${m.id}, '${escAttr(m.name)}', ${m.price})">Beli</button>
                        </div>
                    </div>
                `;
    }).join('');
}

function onCompareSearchInput() {
    const val = document.getElementById('compare-search').value;
    document.querySelectorAll('.compare-chip').forEach(c => c.classList.remove('active'));
    performCompareSearch(val);
}

function searchCompareChip(category) {
    const searchInput = document.getElementById('compare-search');
    searchInput.value = category;

    document.querySelectorAll('.compare-chip').forEach(c => {
        if (c.textContent.includes(category)) {
            c.classList.add('active');
        } else {
            c.classList.remove('active');
        }
    });

    performCompareSearch(category);
}

function renderCompare() {
    const val = document.getElementById('compare-search').value;
    performCompareSearch(val);
}

/* ===== ORDER HISTORY ===== */
async function renderOrders() {
    const listContainer = document.getElementById('orders-list');
    listContainer.innerHTML = '<div style="padding: 2rem; text-align: center;">Memuat riwayat pesanan...</div>';

    if (!loggedUser || !loggedUser.id) {
        listContainer.innerHTML = '<div style="padding: 2rem; text-align: center;">Silakan login kembali.</div>';
        return;
    }

    try {
        const res = await fetch('api.php?action=get_orders');
        const data = await res.json();

        if (data.success && data.orders && data.orders.length > 0) {
            listContainer.innerHTML = data.orders.map(o => `
                        <div style="background: var(--summary-bg); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 0.5rem; margin-bottom: 0.5rem;">
                                <strong>Pesanan #${escHTML(String(o.id))}</strong>
                                <span>${escHTML(new Date(o.created_at).toLocaleString('id-ID'))}</span>
                            </div>
                            ${(o.items || []).map(i => `
                                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                    <span>${escHTML(i.food_name)} x${parseInt(i.qty)}</span>
                                    <span>${fmt(parseInt(i.qty) * parseInt(i.price))}</span>
                                </div>
                            `).join('')}
                            <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(0,0,0,0.1); padding-top: 0.5rem; margin-top: 0.5rem; font-weight: bold;">
                                <span>Total</span>
                                <span>${fmt(parseInt(o.total_price))}</span>
                            </div>
                        </div>
                    `).join('');
        } else {
            listContainer.innerHTML = '<div style="padding: 2rem; text-align: center;">Belum ada pesanan.</div>';
        }
    } catch (e) {
        listContainer.innerHTML = '<div style="padding: 2rem; text-align: center; color: red;">Gagal memuat pesanan.</div>';
    }
}

/* ===== BULK FOOD IMPORTER ===== */
function renderImport() {
    document.getElementById('import-preview-container').style.display = 'none';
    window.parsedImportItems = [];
}

function parseBulkImportText() {
    const text = document.getElementById('import-textarea').value.trim();
    const previewContainer = document.getElementById('import-preview-container');
    const previewBody = document.getElementById('import-preview-body');
    const confirmBtn = document.getElementById('btn-confirm-import');

    if (!text) {
        showToast('Masukkan data teks terlebih dahulu!', 'error');
        return;
    }

    const lines = text.split('\n');
    let parsedItems = [];
    let errorLines = [];

    lines.forEach((line, index) => {
        if (!line.trim()) return;

        // Split by tab (default for excel copy-paste)
        let parts = line.split('\t');
        if (parts.length < 3) {
            // Coba split by comma
            parts = line.split(',');
        }

        // Jika masih kurang, coba split by spasi ganda (multiple spaces)
        if (parts.length < 3) {
            parts = line.split(/\s{2,}/);
        }

        if (parts.length >= 3) {
            const col1 = parts[0].trim();
            // Lewati header row jika terdeteksi
            if (col1.toLowerCase() === 'no' || col1.toLowerCase() === 'toko' || col1.toLowerCase() === 'nama produk' || col1.toLowerCase() === 'nama') {
                return;
            }

            let store = '';
            let product = '';
            let price = 0;
            let variant = '';

            // Jika kolom pertama adalah nomor urut angka
            if (!isNaN(col1) && parts.length >= 4) {
                store = parts[1].trim();
                product = parts[2].trim();
                price = parseInt(parts[3].replace(/[^0-9]/g, ''));
                variant = parts[4] ? parts[4].trim() : product;
            } else {
                // Layout biasa: Store, Product, Price, Variant
                store = parts[0].trim();
                product = parts[1].trim();
                price = parseInt(parts[2].replace(/[^0-9]/g, ''));
                variant = parts[3] ? parts[3].trim() : product;
            }

            if (store && product && !isNaN(price)) {
                parsedItems.push({ store, product, price, variant });
            } else {
                errorLines.push(`Baris ${index + 1}: Data tidak valid (toko/produk/harga kosong)`);
            }
        } else {
            errorLines.push(`Baris ${index + 1}: format tidak sesuai (minimal 3 kolom)`);
        }
    });

    if (parsedItems.length === 0) {
        showToast('Tidak ada data valid yang berhasil diproses!', 'error');
        previewContainer.style.display = 'none';
        return;
    }

    // Tampilkan preview tabel
    previewBody.innerHTML = parsedItems.map((item, idx) => `
                <tr>
                    <td>${idx + 1}</td>
                    <td><strong>${escHTML(item.store)}</strong></td>
                    <td>${escHTML(item.product)}</td>
                    <td>${fmt(item.price)}</td>
                    <td>${escHTML(item.variant)}</td>
                    <td><span style="color:#27ae60; font-weight: bold;">Valid</span></td>
                </tr>
            `).join('');

    previewContainer.style.display = 'block';
    window.parsedImportItems = parsedItems;

    if (errorLines.length > 0) {
        showToast(`${errorLines.length} baris dilewati karena format tidak sesuai.`, 'error');
    } else {
        showToast(`Berhasil membaca ${parsedItems.length} baris data!`);
    }
}

async function submitBulkImport() {
    if (!window.parsedImportItems || window.parsedImportItems.length === 0) {
        showToast('Tidak ada data untuk diimport!', 'error');
        return;
    }

    const btn = document.getElementById('btn-confirm-import');
    btn.disabled = true;
    btn.textContent = 'Sedang mengimport ke database...';

    try {
        const res = await fetch('api.php?action=bulk_add_foods', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: window.parsedImportItems })
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message);
            document.getElementById('import-textarea').value = '';
            document.getElementById('import-preview-container').style.display = 'none';
            window.parsedImportItems = [];
            await refreshData();
            navigate('dashboard');
        } else {
            showToast(data.message, 'error');
        }
    } catch (e) {
        showToast('Gagal mengirim data import.', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Konfirmasi Import ke Database';
    }
}

//utk rndr y
function renderDashboard() {
    renderPromoSlider();
    startPromoSlider();

    document.getElementById('dashboard-list').innerHTML = stores.map(s => `
                <div class="toko-card" onclick="navigate('catalog', ${s.id})">
                    <div class="card-actions" onclick="event.stopPropagation()">
                        <button class="card-action-btn edit-btn" onclick="openStoreModal(${s.id})" title="Edit"><i class="fas fa-pen"></i></button>
                        <button class="card-action-btn delete-btn" onclick="openDeleteModal('store', ${s.id}, '${escAttr(s.name)}')" title="Hapus"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="toko-card-icon">${escHTML(s.icon)}</div>
                    <div class="toko-card-name">${escHTML(s.name)}</div>
                    <div class="toko-card-desc">${escHTML(s.info)}</div>
                </div>
            `).join('');
}

function renderPromoSlider() {
    const slider = document.getElementById('promo-slider');
    slider.innerHTML = promoProducts.map(p => `
                <div class="promo-item" onclick="navigate('catalog', ${p.storeId})">
                    <div class="promo-item-img">${escHTML(p.icon)}</div>
                    <div class="promo-item-info">
                        <div class="promo-badge">${escHTML(p.label)}</div>
                        <div class="promo-item-title">${escHTML(p.name)}</div>
                        <div class="promo-item-price">
                            <span class="price-old">${fmt(p.oldPrice)}</span>
                            <span class="price-new">${fmt(p.newPrice)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
}

function startPromoSlider() {
    if (promoInterval) clearInterval(promoInterval);
    promoIndex = 0;
    updateSliderPos();

    promoInterval = setInterval(() => {
        promoIndex = (promoIndex + 1) % promoProducts.length;
        updateSliderPos();
    }, 3000);
}

function updateSliderPos() {
    const slider = document.getElementById('promo-slider');
    if (slider) {
        slider.style.transform = `translateX(-${promoIndex * 100}%)`;
    }
}

function renderListToko() {
    document.getElementById('list-toko-title').textContent = 'Semua Toko';
    document.getElementById('toko-grid').innerHTML = stores.map(s => `
                <div class="toko-card" onclick="navigate('catalog', ${s.id})">
                    <div class="card-actions" onclick="event.stopPropagation()">
                        <button class="card-action-btn edit-btn" onclick="openStoreModal(${s.id})" title="Edit"><i class="fas fa-pen"></i></button>
                        <button class="card-action-btn delete-btn" onclick="openDeleteModal('store', ${s.id}, '${escAttr(s.name)}')" title="Hapus"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="toko-card-icon">${escHTML(s.icon)}</div>
                    <div class="toko-card-name">${escHTML(s.name)}</div>
                    <div class="toko-card-desc">${escHTML(s.info)}</div>
                </div>
            `).join('');
}

function renderMitra() {
    document.getElementById('mitra-grid').innerHTML = mitras.map(m => `
                <div class="mitra-card" onclick="clickMitra('${escAttr(m.id)}', '${escAttr(m.name)}')">
                    <div class="card-actions" onclick="event.stopPropagation()">
                        <button class="card-action-btn edit-btn" onclick="openMitraModal('${escAttr(m.id)}')" title="Edit"><i class="fas fa-pen"></i></button>
                        <button class="card-action-btn delete-btn" onclick="openDeleteModal('mitra', '${escAttr(m.id)}', '${escAttr(m.name)}')" title="Hapus"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="mitra-name">${escHTML(m.name)}</div>
                </div>
            `).join('');
}

function clickMitra(mitraId, mitraName) {
    currentMitra = { mitraId, mitraName };
    navigate('toko-mitra', { mitraId, mitraName });
}

function renderTokoMitra(data) {
    const { mitraId, mitraName } = data || {};
    document.getElementById('toko-mitra-label').textContent = mitraName || 'Mitra';
    const list = stores.filter(s => s.mitra_id === mitraId);
    document.getElementById('toko-mitra-grid').innerHTML = list.map(s => `
                <div class="toko-card" onclick="navigate('catalog', ${s.id})">
                    <div class="card-actions" onclick="event.stopPropagation()">
                        <button class="card-action-btn edit-btn" onclick="openStoreModal(${s.id})" title="Edit"><i class="fas fa-pen"></i></button>
                        <button class="card-action-btn delete-btn" onclick="openDeleteModal('store', ${s.id}, '${escAttr(s.name)}')" title="Hapus"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="toko-card-icon">${escHTML(s.icon)}</div>
                    <div class="toko-card-name">${escHTML(s.name)}</div>
                    <div class="toko-card-desc">${escHTML(s.info)}</div>
                </div>
            `).join('');
}

function renderCatalog(storeId) {
    if (storeId) currentStoreId = storeId;
    const store = stores.find(s => s.id === currentStoreId);
    if (!store) return;
    document.getElementById('catalog-store-title').textContent = store.name;
    document.getElementById('catalog-store-sub').textContent = store.info;
    document.getElementById('food-grid').innerHTML = (foods[currentStoreId] || []).map(f => {
        let jenisColor = '#e0e0e0'; let jenisTextColor = '#333';
        if (f.jenis === 'Minuman') { jenisColor = '#4fc3f7'; jenisTextColor = '#fff'; }
        else if (f.jenis === 'Topping') { jenisColor = '#ffb74d'; jenisTextColor = '#fff'; }
        else { jenisColor = '#81c784'; jenisTextColor = '#fff'; }
        return `
        <div class="food-card">
            <div class="card-actions">
                <button class="card-action-btn edit-btn" onclick="openFoodModal(${f.id})" title="Edit"><i class="fas fa-pen"></i></button>
                <button class="card-action-btn delete-btn" onclick="openDeleteModal('food', ${f.id}, '${escAttr(f.name)}')" title="Hapus"><i class="fas fa-trash"></i></button>
            </div>
            <div class="food-name">${escHTML(f.name)}</div>
            <div class="food-price">${fmt(f.price)} <span style="font-size: 0.8em; background: ${jenisColor}; color: ${jenisTextColor}; padding: 2px 8px; border-radius: 4px; margin-left: 5px;">${escHTML(f.jenis || 'Makanan')}</span></div>
            <button class="add-btn" onclick="addToCart(${f.id}, '${escAttr(f.name)}', ${f.price})">Tambah ke Keranjang</button>
        </div>
    `}).join('');
}

// kantong belanja 
function addToCart(id, name, price) {
    const ex = cart.find(i => i.id === id);
    if (ex) ex.qty++;
    else cart.push({ id, name, price, qty: 1 });
    updateBadge();
    showToast(`${name} ditambahkan ke keranjang!`);
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
    updateBadge();
    if (currentView === 'cart') renderCart();
}

function updateBadge() {
    const totalQty = cart.reduce((s, i) => s + i.qty, 0);
    const badge = document.getElementById('cart-badge');
    badge.textContent = totalQty;
    badge.style.display = totalQty > 0 ? 'inline' : 'none';

    let floatingCart = document.getElementById('floating-cart-bar');
    if (!floatingCart) {
        floatingCart = document.createElement('div');
        floatingCart.id = 'floating-cart-bar';
        floatingCart.className = 'floating-cart-bar';
        floatingCart.onclick = () => navigate('cart');
        document.body.appendChild(floatingCart);
    }
    if (totalQty > 0) {
        const totalPrice = cart.reduce((s, i) => s + i.price * i.qty, 0);
        floatingCart.innerHTML = `<span>🛒 ${totalQty} item</span><span>${fmt(totalPrice)} <i class="fas fa-chevron-right"></i></span>`;
        floatingCart.classList.remove('hidden');
        floatingCart.style.display = 'flex';
    } else {
        floatingCart.classList.add('hidden');
        floatingCart.style.display = 'none';
    }
}

function renderCart() {
    const layout = document.getElementById('cart-layout');
    const empty = document.getElementById('cart-empty');

    if (cart.length === 0) {
        layout.style.display = 'none';
        empty.style.display = 'block';
        return;
    }
    layout.style.display = 'grid';
    empty.style.display = 'none';

    document.getElementById('cart-items').innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="cart-thumb"></div>
            <div class="cart-item-info">
                <div class="cart-item-name"><strong>nama makanan :</strong> ${escHTML(item.name)}</div>
                <div class="cart-item-addons"></div>
            </div>
            <div class="qty-controls">
                <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
            </div>
        </div>
    `).join('');

    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('cart-summary').innerHTML = `
        ${cart.map(i => `<div class="summary-item">${escHTML(i.name)} x${i.qty}</div>`).join('')}
        <div class="summary-total">total : ${fmt(total)}</div>
    `;
}

// bayar 
function startPayment() {
    if (cart.length === 0) { alert('Keranjang masih kosong!'); return; }
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('qris-total').textContent = fmt(total);
    // kiris
    const qrGrid = document.getElementById('qr-grid');
    qrGrid.style.display = 'flex';
    qrGrid.innerHTML = `<img src="qris.png" alt="QRIS" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">`;
    document.getElementById('modal-qris').classList.add('active');
}

async function simulatePayment() {
    if (cart.length === 0) return;
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.querySelector('#modal-qris .modal-btn-primary').textContent = 'Memproses...';
    try {
        // Kirim hanya food id + qty ke server. Server akan hitung harga sendiri dari DB.
        const checkoutItems = cart.map(i => ({ id: i.id, qty: i.qty }));
        const res = await fetch('api.php?action=checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                items: checkoutItems
            })
        });
        const data = await res.json();
        document.querySelector('#modal-qris .modal-btn-primary').textContent = 'Simulasi Pembayaran Berhasil';
        if (data.success) {
            closeModal('modal-qris');
            document.getElementById('modal-success').classList.add('active');
        } else {
            alert('Gagal: ' + data.message);
        }
    } catch (e) {
        document.querySelector('#modal-qris .modal-btn-primary').textContent = 'Simulasi Pembayaran Berhasil';
        alert('Terjadi kesalahan koneksi.');
    }
}

function finishPayment() {
    closeModal('modal-success');
    cart = [];
    updateBadge();
    navigate('dashboard');
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }
