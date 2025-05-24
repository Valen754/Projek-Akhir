<?php
include '../../koneksi.php'; // Koneksi ke database
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran 'kasir'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../../login/login.php");
    exit();
}

// Query untuk menghitung jumlah menu berdasarkan kategori
$countQuery = "SELECT type, COUNT(*) as total FROM menu WHERE quantity > 0 GROUP BY type";
$countResult = $conn->query($countQuery);

$menuCounts = [];
if ($countResult->num_rows > 0) {
    while ($countRow = $countResult->fetch_assoc()) {
        $menuCounts[$countRow['type']] = $countRow['total'];
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tapal Kuda</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');

            * {
                box-sizing: border-box;
            }

            html,
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                height: 100%;
                font-family: 'Inter', sans-serif;
                background-color: #1c2431;
            }


            .container {
                display: flex;
                height: 100vh;
            }

            /* Sidebar */
            .sidebar {
                background-color: #222b3a;
                width: 80px;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 32px 0;
                gap: 40px;
                border-top-left-radius: 12px;
                border-bottom-left-radius: 12px;
                height: 100vh;
                position: fixed; /* Fixed position */
                left: 0; /* Align to left */
                top: 0; /* Align to top */
                z-index: 100; /* Higher z-index to be on top */
            }

            .sidebar button {
                background: none;
                border: none;
                color: #e07b6c;
                font-size: 20px;
                cursor: pointer;
            }

            /* Main content */
            main {
                flex: 1;
                padding: 32px;
                display: flex;
                flex-direction: column;
                gap: 24px;
                height: 100vh;
                min-height: 100vh;
                margin-left: 80px; /* Offset for sidebar */
                margin-right: 384px; /* Offset for order panel */
                overflow-y: auto; /* Enable scrolling for main content */
                position: relative;
            }

            main header {
                margin-bottom: 24px;
            }

            main header h1 {
                color: white;
                font-weight: 600;
                font-size: 20px;
                margin: 0 0 4px 0;
            }

            main header p {
                font-size: 14px;
                color: #6b7280;
                margin: 0;
            }

            nav.tabs {
                display: flex;
                align-items: center;
                gap: 24px;
                font-size: 14px;
                font-weight: 600;
            }

            .tab-link {
                background: none;
                border: none;
                color: #6b7280;
                padding-bottom: 4px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                margin-right: 18px;
            }

            .tab-link.active {
                color: #e07b6c;
                border-bottom: 2px solid #e07b6c;
            }

            .tab-link span {
                color: #f7b267;
                font-weight: 400;
                font-size: 13px;
                margin-left: 2px;
            }

            .search-container {
                margin-left: auto;
                position: relative;
                width: 240px;
            }

            .search-container input[type="search"] {
                width: 100%;
                padding: 8px 12px 8px 36px;
                border-radius: 6px;
                border: none;
                background-color: #2a3345;
                color: #9ca3af;
                font-size: 14px;
            }

            .search-container input::placeholder {
                color: #6b7280;
            }

            .search-container .icon-search {
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: #6b7280;
                font-size: 14px;
                pointer-events: none;
            }

            select.dine-in {
                margin-left: 16px;
                background-color: #2a3345;
                border: none;
                border-radius: 6px;
                color: #9ca3af;
                font-size: 14px;
                padding: 8px 12px;
                cursor: pointer;
            }

            /* Choose Dishes */
            section.choose-dishes h2 {
                font-weight: 700;
                color: white;
                margin-bottom: 16px;
            }

            .dishes-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 24px;
            }

            .dish-card {
                background-color: #2a3345;
                border-radius: 12px;
                padding: 24px 24px 32px 24px;
                text-align: center;
                color: #9ca3af;
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
            }

            .dish-card img {
                width: 96px;
                height: 96px;
                border-radius: 50%;
                object-fit: cover;
                margin-bottom: 16px;
            }

            .dish-card h3 {
                color: white;
                font-weight: 600;
                margin: 0 0 8px 0;
                font-size: 16px;
            }

            .dish-card p.price {
                margin: 0 0 4px 0;
                font-size: 14px;
            }

            .dish-card p.available {
                margin: 0;
                font-size: 12px;
                color: #6b7280;
            }

            .dish-card button.add-to-order {
                position: absolute;
                top: 12px;
                right: 12px;
                background: #e07b6c;
                color: #fff;
                border: none;
                border-radius: 50%;
                width: 32px;
                height: 32px;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.2s;
                z-index: 2;
            }

            .dish-card button.add-to-order:hover {
                background: #f7b267;
                color: #222b3a;
            }

            /* Orders panel */
            aside.orders-panel {
                background-color: #2a3345;
                width: 384px;
                border-top-right-radius: 12px;
                border-bottom-right-radius: 12px;
                padding: 32px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100vh;
                position: fixed; /* Fixed position */
                right: 0; /* Align to right */
                top: 0; /* Align to top */
                overflow-y: auto; /* Enable scrolling for panel */
                z-index: 100; /* Higher z-index to be on top */
            }

            aside.orders-panel header h2 {
                color: white;
                font-weight: 600;
                font-size: 16px;
                margin: 0 0 12px 0;
            }

            aside.orders-panel header h2 span {
                color: #6b7280;
                font-weight: 400;
                font-size: 14px;
            }

            nav.order-types {
                display: flex;
                gap: 12px;
                font-size: 12px;
                font-weight: 700;
            }

            nav.order-types button {
                border-radius: 8px;
                border: none;
                padding: 6px 16px;
                cursor: pointer;
                color: #9ca3af;
                background-color: #3f4556;
            }

            nav.order-types button.active {
                background-color: #e07b6c;
                color: white;
            }

            ul.order-list {
                list-style: none;
                padding: 0;
                margin: 24px 0 0 0;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            ul.order-list li {
                display: flex;
                gap: 12px;
                align-items: flex-start;
            }

            ul.order-list li img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                flex-shrink: 0;
            }

            ul.order-list li .order-info {
                flex: 1;
            }

            ul.order-list li .order-info p.name {
                margin: 0 0 4px 0;
                font-weight: 600;
                font-size: 14px;
                color: white;
            }

            ul.order-list li .order-info p.price {
                margin: 0 0 6px 0;
                font-size: 12px;
                color: #6b7280;
            }

            ul.order-list li .order-info input[type="text"] {
                width: 100%;
                background-color: #1c2431;
                border: none;
                border-radius: 6px;
                padding: 6px 12px;
                font-size: 12px;
                color: #9ca3af;
            }

            ul.order-list li .order-info input::placeholder {
                color: #6b7280;
            }

            ul.order-list li .order-qty-delete {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }
            
            ul.order-list li .order-qty-delete .qty-controls {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            ul.order-list li .order-qty-delete .qty-controls button {
                background-color: #3f4556;
                color: #9ca3af;
                border: none;
                border-radius: 4px;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 12px;
                transition: background-color 0.2s;
            }
            ul.order-list li .order-qty-delete .qty-controls button:hover {
                background-color: #555;
            }

            ul.order-list li .order-qty-delete span.qty {
                background-color: #1c2431;
                color: #9ca3af;
                font-size: 12px;
                border-radius: 8px;
                padding: 2px 8px;
                user-select: none;
            }

            ul.order-list li .order-qty-delete button.delete-btn {
                background: none;
                border: none;
                color: #e07b6c;
                cursor: pointer;
                font-size: 14px;
            }

            ul.order-list li .order-qty-delete button.delete-btn:hover {
                color: #f28a7a;
            }

            /* Footer */
            aside.orders-panel footer {
                border-top: 1px solid #374151;
                padding-top: 24px;
            }

            aside.orders-panel footer .discount,
            aside.orders-panel footer .subtotal {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 8px;
            }

            aside.orders-panel footer .subtotal {
                font-weight: 700;
                color: white;
                font-size: 16px;
                margin-bottom: 24px;
            }

            aside.orders-panel footer button {
                width: 100%;
                background-color: #e07b6c;
                border: none;
                border-radius: 12px;
                padding: 12px 0;
                font-weight: 700;
                color: white;
                font-size: 16px;
                cursor: pointer;
            }

            aside.orders-panel footer button:hover {
                background-color: #d46a5a;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .container {
                    flex-direction: column;
                    min-height: auto;
                    border-radius: 0;
                }

                .sidebar {
                    flex-direction: row;
                    width: 100%;
                    padding: 16px 0;
                    gap: 24px;
                    border-radius: 0;
                    justify-content: center;
                    position: static; /* Change to static for mobile */
                    height: auto;
                }

                main {
                    padding: 16px;
                    margin-left: 0; /* Remove offset for mobile */
                    margin-right: 0; /* Remove offset for mobile */
                }

                aside.orders-panel {
                    width: 100%;
                    border-radius: 0;
                    padding: 16px;
                    margin-top: 24px;
                    position: static; /* Change to static for mobile */
                    height: auto;
                }

                .dishes-grid {
                    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                    gap: 16px;
                }
            }
        </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container" role="main">
        <?php include '../../views/kasir/sidebar.php'; /*cite: valen754/projek-akhir/Projek-Akhir-c080af7e4fecb96a5f49502d626f1fcf9c276a3c/WEBPRO/views/kasir/sidebar.php*/ ?>
       
        <main>
            <header>
                <h1>Tapal Kuda</h1>
                <p>Tuesday, 29 April 2025</p>
            </header>
            <nav class="tabs" aria-label="Dish categories">
                <a href="kasir.php" class="tab-link <?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>">
                    Coffe <span>(<?php echo isset($menuCounts['kopi']) ? $menuCounts['kopi'] : 0; ?>)</span>
                </a>
                <a href="kasir.php?type=minuman" class="tab-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>">
                    Non Coffe <span>(<?php echo isset($menuCounts['minuman']) ? $menuCounts['minuman'] : 0; ?>)</span>
                </a>
                <a href="kasir.php?type=makanan_berat" class="tab-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>">
                    Foods <span>(<?php echo isset($menuCounts['makanan_berat']) ? $menuCounts['makanan_berat'] : 0; ?>)</span>
                </a>
                <a href="kasir.php?type=cemilan" class="tab-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>">
                    Snacks <span>(<?php echo isset($menuCounts['cemilan']) ? $menuCounts['cemilan'] : 0; ?>)</span>
                </a>
                <div class="search-container">
                    <input type="search" placeholder="Search for food, coffe, etc.." aria-label="Search for food, coffee, etc." id="searchInput" />
                    <i class="fas fa-search icon-search" aria-hidden="true"></i>
                </div>
            </nav>
            <?php
            // Query dasar untuk menampilkan menu berdasarkan kategori
            $sql = "SELECT * FROM menu"; // Default: semua menu

            if (isset($_GET['type']) && !empty($_GET['type'])) {
                $type = $conn->real_escape_string($_GET['type']); // Sanitasi input
                $sql .= " WHERE type = '$type'";
            }
            // Filter hanya menu yang statusnya 'tersedia' dan kuantitas > 0
            // Jika sudah ada WHERE sebelumnya, gunakan AND
            if (strpos($sql, 'WHERE') !== false) {
                 $sql .= " AND status = 'tersedia'";
            } else {
                 $sql .= " WHERE status = 'tersedia'";
            }
            $sql .= " ORDER BY nama ASC"; // Tambahkan pengurutan

            $result = $conn->query($sql);
            ?>
            <section class="choose-dishes" aria-label="Choose Dishes">
                <h2>Choose Dishes</h2>
                <div class="dishes-grid" id="dishesGrid">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <article class="dish-card" 
                             data-id="<?php echo $row['id']; ?>" 
                             data-name="<?php echo htmlspecialchars($row['nama']); ?>" 
                             data-price="<?php echo $row['price']; ?>" 
                             data-img="<?php echo $row['url_foto']; ?>"
                             data-initial-quantity="<?php echo $row['quantity']; ?>"> <img src="../../asset/<?php echo $row['url_foto']; ?>" alt="<?php echo $row['nama']; ?>" width="96" height="96" />
                        <h3><?php echo $row['nama']; ?></h3>
                        <p class="price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <p class="available">Stok: <span class="stock-qty"><?php echo $row['quantity']; ?></span> tersedia</p>
                        <button class="add-to-order" type="button" title="Tambah ke pesanan" <?php echo ($row['quantity'] <= 0) ? 'disabled' : ''; ?>>
                            <i class="fas fa-plus"></i>
                        </button>
                    </article>
                    <?php
                        }
                    } else {
                        echo "<p>Menu tidak tersedia.</p>";
                    }
                    ?>
                </div>
            </section>
        </main>
        <aside class="orders-panel" aria-label="Orders panel">
            <header>
                <h2>Orders <span id="order-id-display">#New Order</span></h2>
            </header>
            <ul class="order-list">
                <li style="color: #9ca3af; text-align: center;">Belum ada pesanan</li>
            </ul>
            <footer>
                <div class="subtotal">
                    <span>Sub total</span>
                    <span id="final-subtotal">Rp. 0</span>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" id="checkoutButton" disabled>Continue to Payment</button>
            </footer>

            <div class="modal" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel" style="color: black;">Pilih Metode Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="color: black;">
                            <div class="mb-3">
                                <label for="customerNameInput" class="form-label">Nama Pelanggan (opsional):</label>
                                <input type="text" class="form-control" id="customerNameInput" placeholder="Masukkan nama pelanggan">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran:</label>
                                <div>
                                    <input type="radio" id="paymentCash" name="paymentMethod" value="cash" checked>
                                    <label for="paymentCash">Tunai</label>
                                </div>
                                <div>
                                    <input type="radio" id="paymentCard" name="paymentMethod" value="card">
                                    <label for="paymentCard">Kartu Debit/Kredit</label>
                                </div>
                                <div>
                                    <input type="radio" id="paymentEwallet" name="paymentMethod" value="e-wallet">
                                    <label for="paymentEwallet">E-Wallet (QRIS)</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="orderNotesInput" class="form-label">Catatan Pesanan (opsional):</label>
                                <textarea class="form-control" id="orderNotesInput" rows="3" placeholder="Tambahkan catatan untuk pesanan ini"></textarea>
                            </div>
                            <p>Total yang harus dibayar: <strong id="modal-total-amount">Rp 0</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-success" id="processPaymentBtn">Proses Pembayaran</button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let orders = {}; // Menyimpan pesanan dalam bentuk objek {menu_id: {name, price, img, qty, item_notes, initial_stock}}
        
        // Fungsi untuk merender ulang daftar pesanan di panel
        function renderOrders() {
            const orderList = document.querySelector('.order-list');
            orderList.innerHTML = ''; // Kosongkan daftar yang ada
            let subtotal = 0;

            if (Object.keys(orders).length === 0) {
                orderList.innerHTML = '<li style="color: #9ca3af; text-align: center;">Belum ada pesanan</li>';
            } else {
                for (const key in orders) {
                    const item = orders[key];
                    subtotal += item.price * item.qty;

                    orderList.innerHTML += `
                        <li>
                            <img src="../../asset/${item.img}" alt="${item.name}" width="40" height="40" />
                            <div class="order-info">
                                <p class="name">${item.name}</p>
                                <p class="price">Rp. ${parseFloat(item.price).toLocaleString('id-ID')}</p>
                                <input type="text" placeholder="Order Note..." aria-label="Order note for ${item.name}" value="${item.item_notes || ''}" onchange="updateItemNote('${key}', this.value)" />
                            </div>
                            <div class="order-qty-delete">
                                <div class="qty-controls">
                                    <button aria-label="Decrease quantity" type="button" onclick="adjustOrderQuantity('${key}', -1)"><i class="fas fa-minus"></i></button>
                                    <span class="qty">${item.qty}</span>
                                    <button aria-label="Increase quantity" type="button" onclick="adjustOrderQuantity('${key}', 1)"><i class="fas fa-plus"></i></button>
                                </div>
                                <button class="delete-btn" aria-label="Delete ${item.name} order" type="button" onclick="removeOrder('${key}')"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </li>
                    `;
                }
            }
            // Update subtotal di footer panel
            document.getElementById('final-subtotal').textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');
            document.getElementById('modal-total-amount').textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');

            // Kontrol tombol "Continue to Payment"
            const checkoutButton = document.getElementById('checkoutButton');
            if (subtotal > 0) {
                checkoutButton.removeAttribute('disabled');
            } else {
                checkoutButton.setAttribute('disabled', 'disabled');
            }
        }

        // Fungsi untuk menambahkan item ke pesanan
        function addOrder(id, name, price, img, currentStockElement, initialQuantity) {
            let currentDisplayedStock = parseInt(currentStockElement.textContent);

            if (currentDisplayedStock <= 0) {
                alert('Stok ' + name + ' habis!');
                return;
            }

            if (orders[id]) {
                if (orders[id].qty < initialQuantity) { // Batasi penambahan hingga stok awal
                    orders[id].qty += 1;
                    currentStockElement.textContent = currentDisplayedStock - 1; // Kurangi stok di UI
                } else {
                    alert('Stok ' + name + ' tidak mencukupi untuk ditambah lagi.');
                }
            } else {
                orders[id] = { 
                    id: id, 
                    name: name, 
                    price: Number(price), 
                    img: img, 
                    qty: 1, 
                    item_notes: '',
                    initial_stock: initialQuantity // Simpan stok awal untuk referensi
                };
                currentStockElement.textContent = currentDisplayedStock - 1; // Kurangi stok di UI
            }
            renderOrders();
        }

        // Fungsi untuk menyesuaikan kuantitas item dalam pesanan
        function adjustOrderQuantity(id, change) {
            const dishCard = document.querySelector(`.dish-card[data-id="${id}"]`);
            const stockQtyElement = dishCard.querySelector('.stock-qty');
            let currentDisplayedStock = parseInt(stockQtyElement.textContent);
            let itemInitialStock = orders[id].initial_stock; // Stok awal dari database

            if (orders[id]) {
                if (change > 0) { // Menambah kuantitas
                    if (orders[id].qty < itemInitialStock) { // Pastikan tidak melebihi stok awal
                        orders[id].qty += change;
                        stockQtyElement.textContent = currentDisplayedStock - change; // Kurangi stok di UI
                    } else {
                        alert('Stok ' + orders[id].name + ' tidak mencukupi.');
                    }
                } else if (change < 0) { // Mengurangi kuantitas
                    if (orders[id].qty > 1) { // Pastikan tidak kurang dari 1
                        orders[id].qty += change;
                        stockQtyElement.textContent = currentDisplayedStock - change; // Tambah stok di UI
                    } else if (orders[id].qty === 1) { // Jika sudah 1, hapus saja
                        removeOrder(id);
                        return; // Keluar dari fungsi karena item sudah dihapus
                    }
                }
                
                // Jika setelah penyesuaian kuantitas item di orders menjadi 0 atau kurang (seharusnya ditangani oleh kondisi di atas)
                if (orders[id] && orders[id].qty <= 0) {
                    removeOrder(id); // Pastikan item dihapus jika kuantitas menjadi 0
                } else {
                    renderOrders(); // Render ulang jika item masih ada
                }
            }
        }

        // Fungsi untuk menghapus item dari pesanan
        function removeOrder(id) {
            if (orders[id]) {
                const dishCard = document.querySelector(`.dish-card[data-id="${id}"]`);
                const stockQtyElement = dishCard.querySelector('.stock-qty');
                let currentDisplayedStock = parseInt(stockQtyElement.textContent);
                
                // Kembalikan stok ke UI
                stockQtyElement.textContent = currentDisplayedStock + orders[id].qty;
                
                delete orders[id];
                renderOrders();
            }
        }

        // Fungsi untuk memperbarui catatan item
        function updateItemNote(id, note) {
            if (orders[id]) {
                orders[id].item_notes = note;
            }
        }

        // Event listener untuk tombol + pada dish-card
        document.querySelectorAll('.add-to-order').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const card = this.closest('.dish-card');
                const id = card.getAttribute('data-id');
                const name = card.getAttribute('data-name');
                const price = card.getAttribute('data-price');
                const img = card.getAttribute('data-img');
                const initialQuantity = parseInt(card.getAttribute('data-initial-quantity')); // Ambil kuantitas awal
                const currentStockElement = card.querySelector('.stock-qty');
                addOrder(id, name, price, img, currentStockElement, initialQuantity);
            });
        });

        // Event listener untuk tombol "Proses Pembayaran" di modal
        document.getElementById('processPaymentBtn').addEventListener('click', function() {
            const customerName = document.getElementById('customerNameInput').value;
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const orderNotes = document.getElementById('orderNotesInput').value;
            const totalAmountText = document.getElementById('final-subtotal').textContent;
            // Bersihkan format Rupiah untuk mendapatkan angka
            const totalAmount = parseFloat(totalAmountText.replace('Rp. ', '').replace(/\./g, '').replace(/,/g, ''));


            if (Object.keys(orders).length === 0) {
                alert('Tidak ada item dalam pesanan.');
                return;
            }
            if (totalAmount <= 0) {
                alert('Total pembayaran harus lebih dari Rp 0.');
                return;
            }

            // Siapkan data untuk dikirim ke server
            const orderData = {
                customer_name: customerName,
                payment_method: paymentMethod,
                order_notes: orderNotes,
                total_amount: totalAmount,
                items: Object.values(orders) // Mengirim detail item dari objek 'orders'
            };

            // Kirim data menggunakan Fetch API
            fetch('logic/process_kasir_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pesanan berhasil diproses!');
                    // Reset order setelah sukses
                    orders = {};
                    renderOrders();
                    // Tutup modal
                    var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                    paymentModal.hide();
                    // Opsional: Refresh halaman untuk mengambil data menu terbaru dari DB
                    window.location.reload(); 
                } else {
                    alert('Gagal memproses pesanan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat berkomunikasi dengan server.');
            });
        });

        // Update total di modal pembayaran saat modal dibuka
        document.getElementById('paymentModal').addEventListener('show.bs.modal', function () {
            const currentSubtotalText = document.querySelector('#final-subtotal').textContent;
            document.getElementById('modal-total-amount').textContent = currentSubtotalText;
            // Reset input di modal setiap kali dibuka
            document.getElementById('customerNameInput').value = '';
            document.getElementById('paymentCash').checked = true; // Default ke tunai
            document.getElementById('orderNotesInput').value = '';
        });

        // Fungsi pencarian real-time
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.dish-card').forEach(card => {
                const dishName = card.querySelector('h3').textContent.toLowerCase();
                const cardType = card.closest('section').querySelector('nav.tabs a.active').getAttribute('href').split('=')[1] || 'all'; // Ambil tipe kategori aktif
                const actualType = card.dataset.type; // Tambahkan data-type ke dish-card di PHP jika perlu

                // Untuk saat ini, asumsikan semua menu ditampilkan di satu grid jika tidak ada filter kategori
                // Jika ada filter kategori, pastikan ini tidak mengganggu
                // Ini akan memfilter semua yang ditampilkan saat ini
                if (dishName.includes(searchTerm)) {
                    card.style.display = 'flex'; // Tampilkan kartu
                } else {
                    card.style.display = 'none'; // Sembunyikan kartu
                }
            });
        });

        // Inisialisasi awal saat halaman dimuat
        renderOrders();
    </script>
</body>

</html>