<?php
include '../../views/header.php';
include '../../koneksi.php';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Menu</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #checkoutModal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 36, 50, 0.85);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        #checkoutModal.active {
            display: flex;
        }

        #checkoutForm {
            background: #222b3a;
            padding: 32px;
            border-radius: 12px;
            max-width: 340px;
            width: 100%;
            margin: auto;
            box-shadow: 0 2px 24px #0006;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        #checkoutForm h2 {
            margin-bottom: 10px;
            text-align: center;
        }

        #checkoutForm label {
            font-size: 15px;
            margin-bottom: 4px;
            display: block;
        }

        #checkoutForm input[type="text"],
        #checkoutForm select {
            width: 100%;
            margin-bottom: 8px;
            padding: 8px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            box-sizing: border-box;
        }

        #checkoutForm .radio-group {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-bottom: 8px;
        }

        #checkoutForm input[type="radio"] {
            margin-right: 6px;
        }

        .btn-bayar {
            width: 100%;
            background: #e07b6c;
            padding: 12px 0;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            font-size: 16px;
            margin-top: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-bayar:hover {
            background: #d45a4c;
        }

        .btn-batal {
            width: 100%;
            margin-top: 8px;
            padding: 10px 0;
            border: none;
            border-radius: 10px;
            background: #222b3a;
            color: #e07b6c;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-batal:hover {
            background: #333d50;
        }

        .add-to-cart-btn {
            cursor: pointer !important;
            z-index: 2;
            position: relative;
            pointer-events: auto;
            transition: background 0.2s;
        }

        #cartInputNote {
            border: 1px solid #ccc;
            padding: 6px;
            border-radius: 5px;
            resize: vertical;
            font-size: 14px;
            box-sizing: border-box;
        }

        #cartInputQty {
            width: 60px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        #cartModal {
            transition: background-color 0.3s ease;
        }

        #cartModal:target {
            background-color: rgba(0, 0, 0, 0.7);
        }

        #cartItems {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }

        .cart-item .item-info {
            flex: 1;
            margin-left: 16px;
            font-size: 18px;
            /* Increased font size */
            color: #333;
        }

        .cart-item .item-actions {
            display: flex;
            gap: 12px;
        }

        .cart-item .item-actions button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #f39c12;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .cart-item .item-actions button:hover {
            background-color: #e67e22;
        }

        #cartModal {
            transition: background-color 0.3s ease;
        }

        #cartModal:target {
            background-color: rgba(0, 0, 0, 0.7);
        }

        #cartItems {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 60px;
            /* Adjusted size */
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        .cart-item .item-info {
            flex: 1;
            margin-left: 12px;
            font-size: 16px;
            color: #333;
        }

        .cart-item .item-actions {
            display: flex;
            gap: 8px;
        }

        .cart-item .item-actions button {
            padding: 4px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #f39c12;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .cart-item .item-actions button:hover {
            background-color: #e67e22;
        }
    </style>
</head>

<body>
    <?php


    // Query untuk menghitung jumlah menu berdasarkan kategori
    $countQuery = "SELECT type, COUNT(*) as total FROM menu GROUP BY type";
    $countResult = $conn->query($countQuery);

    $menuCounts = [];
    if ($countResult->num_rows > 0) {
        while ($countRow = $countResult->fetch_assoc()) {
            $menuCounts[$countRow['type']] = $countRow['total'];
        }
    }

    // Ambil daftar menu yang difavoritkan oleh user yang sedang login
    $favorite_menus = [];
    if ($user_id) {
        $query_favorites = "SELECT menu_id FROM favorites WHERE user_id = $user_id";
        $result_favorites = $conn->query($query_favorites);
        if ($result_favorites) {
            while ($row_fav = $result_favorites->fetch_assoc()) {
                $favorite_menus[] = $row_fav['menu_id'];
            }
        }
    }
    ?>

    <div class="container-banner">
        <div class="overlay"></div>
        <div class="judul">Menu</div>
    </div>

    <div class="container">
        <ul class="nav-pills">
            <div class="kategori">PRODUCT CATEGORIES</div>
            <li>
                <a class="nav-link <?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>"
                    href="menu.php">
                    All <span>(<?php echo array_sum($menuCounts); ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'kopi') ? 'active' : ''; ?>"
                    href="menu.php?type=kopi">
                    Coffe <span>(<?php echo isset($menuCounts['kopi']) ? $menuCounts['kopi'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>"
                    href="menu.php?type=minuman">
                    Non Coffe <span>(<?php echo isset($menuCounts['minuman']) ? $menuCounts['minuman'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>"
                    href="menu.php?type=makanan_berat">
                    Foods
                    <span>(<?php echo isset($menuCounts['makanan_berat']) ? $menuCounts['makanan_berat'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>"
                    href="menu.php?type=cemilan">
                    Snacks <span>(<?php echo isset($menuCounts['cemilan']) ? $menuCounts['cemilan'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'favorit') ? 'active' : ''; ?>"
                    href="menu.php?type=favorit">
                    Favorit <span>(<?php echo count($favorite_menus); ?>)</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="semua">
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM menu WHERE quantity > 0";
                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        $type = $conn->real_escape_string($_GET['type']); // Sanitasi input
                        if ($type === 'favorit' && !empty($favorite_menus)) {
                            $menu_ids = implode(',', $favorite_menus);
                            $sql = "SELECT * FROM menu WHERE id IN ($menu_ids) AND quantity > 0";
                        } elseif ($type !== 'favorit') {
                            $sql .= " AND type = '$type'";
                        }
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="col">
                                <div class="card">
                                    <div class="image-wrapper">
                                        <img src="../../asset/<?php echo htmlspecialchars($row['url_foto']); ?>"
                                            alt="<?php echo htmlspecialchars($row['nama']); ?>">
                                        <div class="btn-overlay" style="display:flex;justify-content:center;gap:10px;">
                                            <!-- Tombol Lihat Detail -->
                                            <a class="btn-icon-round" href="../detail/detail.php?id=<?php echo $row['id']; ?>"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </a>
                                            <!-- Tombol Tambah ke Keranjang -->
                                            <button class="add-to-cart-btn btn-icon-round" data-id="<?php echo $row['id']; ?>"
                                                data-nama="<?php echo htmlspecialchars($row['nama']); ?>"
                                                data-harga="<?php echo $row['price']; ?>"
                                                data-foto="<?php echo htmlspecialchars($row['url_foto']); ?>"
                                                data-stok="<?php echo $row['quantity']; ?>" title="Tambah ke Keranjang">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                            <!-- Tombol Favorit -->
                                            <?php if ($user_id): ?>
                                                <form action="logic/toggle_favorit.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="menu_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn-icon-round"
                                                        style="background:none;border:none;padding:0;"
                                                        title="<?php echo in_array($row['id'], $favorite_menus) ? 'Hapus dari Favorit' : 'Tambah ke Favorit'; ?>">
                                                        <?php if (in_array($row['id'], $favorite_menus)): ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                                fill="#FFD700" class="bi bi-bookmark-heart-fill" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd"
                                                                    d="M8 4.41c1.387-1.425 4.854 1.07 0 4.277C3.146 5.48 6.613 2.986 8 4.412z" />
                                                                <path
                                                                    d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                                fill="currentColor" class="bi bi-bookmark-heart" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd"
                                                                    d="M8 4.41c1.387-1.425 4.854 1.07 0 4.277C3.146 5.48 6.613 2.986 8 4.412z" />
                                                                <path
                                                                    d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                                                            </svg>
                                                        <?php endif; ?>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-title"><?php echo htmlspecialchars($row['nama']); ?></div>
                                        <div class="card-title">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?>
                                        </div>
                                        <div class="card-title" style="color:#6d4c2b;">
                                            Tersedia: <?php echo htmlspecialchars($row['quantity']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>Menu tidak tersedia.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Buka Keranjang -->
    <button id="openCartBtn"
        style="position:fixed;bottom:32px;right:32px;z-index:999;background:#6d4c2b;color:#fff;padding:12px 20px;border:none;border-radius:50px;box-shadow:0 2px 8px rgba(0,0,0,0.15);font-size:18px;">
        <i class="fas fa-shopping-cart"></i> <span id="cartCount">0</span>
    </button>

    <!-- Modal Keranjang -->
    <div id="cartModal"
        style="display:none;position:fixed;z-index:10000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:12px;max-width:450px;margin:80px auto 0;box-shadow:0 8px 16px rgba(0,0,0,0.2);position:relative;display:flex;flex-direction:column;justify-content:space-between;height:80vh;">

            <!-- Close Button -->
            <span id="closeCartModal"
                style="position:absolute;top:16px;right:16px;cursor:pointer;font-size:28px;color:#333;font-weight:bold;">&times;</span>

            <!-- Modal Header -->
            <h3 style="font-size:24px;color:#2c3e50;text-align:center;margin-bottom:20px;">Keranjang Belanja</h3>

            <!-- Cart Items -->
            <div id="cartItems" style="flex:1;overflow-y:auto;margin-bottom:16px;">
                <!-- Cart item examples (should be dynamically added via JS) -->
                <!-- <div class="cart-item">...</div> -->
            </div>

            <!-- Subtotal -->
            <div id="cartSubtotal"
                style="font-size:16px;color:#2c3e50;font-weight:normal;margin-top:8px;text-align:center;">
                <!-- Subtotal akan muncul di sini -->
            </div>
            <!-- Cart Tax -->
            <div id="cartTax" style="font-size:16px;color:#2c3e50;font-weight:normal;margin-top:8px;text-align:center;">
                <!-- Pajak akan muncul di sini -->
            </div>
            <!-- Cart Total -->
            <div id="cartTotal" style="font-size:18px;color:#2c3e50;font-weight:bold;margin-top:8px;text-align:center;">
                <!-- Total price will appear here -->
            </div>

            <!-- Clear Cart Button -->
            <div style="text-align:center;margin-top:16px;">
                <button id="checkoutBtn"
                    style="background:#e07b6c;color:#fff;padding:12px 24px;border:none;border-radius:6px;font-size:16px;width:100%;cursor:pointer;margin-top:10px;">
                    Checkout
                </button>
                <br><br>
                <button id="clearCartBtn"
                    style="width:100%;margin-top:8px;padding:12px 24px;border:none;border-radius:6px;font-size:16px;background:#222b3a;color:#e07b6c; cursor: pointer;">
                    Kosongkan Keranjang
                </button>
            </div>

        </div>
    </div>


    <!-- Modal Notifikasi -->
    <div id="notifModal"
        style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:8px;max-width=350px;margin:120px auto 0;box-shadow:0 2px 8px rgba(0,0,0,0.15);text-align:center;position:relative;">
            <span id="notifModalClose"
                style="position:absolute;top:8px;right:16px;cursor:pointer;font-size:22px;">&times;</span>
            <div id="notifModalMsg" style="color:#155724;font-size:16px;"></div>
        </div>
    </div>
    <?php if (isset($_SESSION['favorit_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var notifModal = document.getElementById('notifModal');
                var notifMsg = document.getElementById('notifModalMsg');
                notifMsg.innerHTML = <?php echo json_encode($_SESSION['favorit_message']); ?>;
                notifModal.style.display = 'block';
                document.getElementById('notifModalClose').onclick = function () {
                    notifModal.style.display = 'none';
                };
                notifModal.onclick = function (e) {
                    if (e.target === notifModal) notifModal.style.display = 'none';
                };
                setTimeout(function () {
                    notifModal.style.display = 'none';
                }, 2000);
            });
        </script>
        <?php unset($_SESSION['favorit_message']); endif; ?>

    <?php
    include '../../views/footer.php';
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Contoh jQuery untuk dropdown sederhana
        $(document).ready(function () {
            $('.dropdown-toggle').click(function () {
                $(this).next('.dropdown-menu').toggle(); // Atau .slideToggle()
            });

            // Tutup dropdown jika klik di luar
            $(document).click(function (event) {
                if (!$(event.target).closest('.dropdown').length) {
                    $('.dropdown-menu').hide();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Logika untuk modal yang sudah ada
            document.querySelectorAll('.openModal').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('modalOverlay').style.display = 'block';
                    document.getElementById('modalImage').src = this.getAttribute('data-foto');
                    document.getElementById('modalName').textContent = this.getAttribute('data-nama');
                    document.getElementById('modalPrice').textContent = parseInt(this.getAttribute('data-harga')).toLocaleString('id-ID');
                    document.getElementById('modalStok').textContent = this.getAttribute('data-stok');
                    document.getElementById('modalMenuId').value = this.getAttribute('data-id');
                    document.getElementById('modalQuantityInput').value = 1;
                });
            });

            document.getElementById('closeModal').addEventListener('click', () => {
                document.getElementById('modalOverlay').style.display = 'none';
            });

            // --- Logika untuk Fitur Favorit ---
            const favoriteButtons = document.querySelectorAll('.favorite-btn');

            favoriteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); // Mencegah link berpindah halaman

                    const menuId = this.dataset.menuId;
                    const icon = this.querySelector('i');

                    // Jika tombol dinonaktifkan (misal karena belum login), tampilkan alert
                    if (this.classList.contains('disabled')) {
                        alert('Anda harus login untuk menambahkan ke favorit.');
                        return;
                    }

                    fetch('logic/toggle_favorit.php', { // Path relatif ke file logic Anda
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'menu_id=' + menuId
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                if (data.action === 'added') {
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                    alert(data.message); // Opsional: tampilkan notifikasi
                                } else if (data.action === 'removed') {
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                    alert(data.message); // Opsional: tampilkan notifikasi
                                }
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses permintaan favorit.');
                        });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // --- KERANJANG ---
            function loadCart() {
                return JSON.parse(localStorage.getItem('cart') || '[]');
            }
            function saveCart(cart) {
                localStorage.setItem('cart', JSON.stringify(cart));
            }
            function updateCartCount() {
                const cart = loadCart();
                document.getElementById('cartCount').textContent = cart.reduce((a, b) => a + b.qty, 0);
            }
            function updateCartTotal() {
                const cart = loadCart();
                let subtotal = 0;
                document.querySelectorAll('.cart-check').forEach((cb, idx) => {
                    if (cb.checked) {
                        subtotal += cart[idx].qty * cart[idx].harga;
                    }
                });
                const tax = Math.round(subtotal * 0.10);
                const total = subtotal + tax;
                document.getElementById('cartSubtotal').textContent = 'Subtotal: Rp ' + subtotal.toLocaleString('id-ID');
                document.getElementById('cartTax').textContent = 'Pajak (10%): Rp ' + tax.toLocaleString('id-ID');
                document.getElementById('cartTotal').textContent = 'Total: Rp ' + total.toLocaleString('id-ID');
            }
            function renderCart() {
                const cart = loadCart();
                const cartItemsDiv = document.getElementById('cartItems');
                if (cart.length === 0) {
                    cartItemsDiv.innerHTML = '<p style="text-align:center;font-size:18px;color:#888;">Keranjang kosong.</p>';
                    document.getElementById('cartTotal').textContent = '';
                    document.getElementById('checkoutBtn').style.display = 'none';
                    return;
                }
                let html = '';
                let total = 0;
                cart.forEach((item, idx) => {
                    html += `
        <div class="cart-item">
            <input type="checkbox" class="cart-check" data-idx="${idx}" checked style="width:20px;height:20px;">
            <img src="../../asset/${item.foto}" alt="">
            <div class="item-info">
                <strong>${item.nama}</strong><br>
                <span>Rp ${item.harga.toLocaleString('id-ID')} x ${item.qty}</span>
                ${item.note ? `<div class="item-note">Catatan: ${item.note}</div>` : ''}
            </div>
            <div class="item-actions">
                <button onclick="removeCartItem(${idx})">Hapus</button>
            </div>
        </div>
        `;
                });
                cartItemsDiv.innerHTML = html;
                updateCartTotal();
                document.getElementById('checkoutBtn').style.display = '';
            }
            window.removeCartItem = function (idx) {
                let cart = loadCart();
                cart.splice(idx, 1);
                saveCart(cart);
                renderCart();
                updateCartCount();
            };


            document.body.addEventListener('click', function (e) {
                if (e.target.closest('.add-to-cart-btn')) {
                    const btn = e.target.closest('.add-to-cart-btn');
                    // Ambil data menu
                    document.getElementById('cartInputId').value = btn.dataset.id;
                    document.getElementById('cartInputNama').value = btn.dataset.nama;
                    document.getElementById('cartInputHarga').value = btn.dataset.harga;
                    document.getElementById('cartInputFoto').value = btn.dataset.foto;
                    document.getElementById('cartInputStok').value = btn.dataset.stok;
                    document.getElementById('cartInputQty').value = 1;
                    document.getElementById('cartInputQty').max = btn.dataset.stok;
                    document.getElementById('cartInputStokInfo').textContent = `(Stok: ${btn.dataset.stok})`;
                    document.getElementById('cartInputNote').value = '';
                    document.getElementById('cartInputModal').style.display = 'block';
                }
            });
            // Event untuk update total saat checkbox dicentang/di-uncheck
            document.getElementById('cartItems').addEventListener('change', function (e) {
                if (e.target.classList.contains('cart-check')) {
                    updateCartTotal();
                }
            });

            // Event untuk tombol Checkout Pilihan
            document.getElementById('checkoutBtn').onclick = function () {
                const cart = loadCart();
                const checkedIdx = [];
                document.querySelectorAll('.cart-check').forEach((cb, idx) => {
                    if (cb.checked) checkedIdx.push(idx);
                });
                if (checkedIdx.length === 0) {
                    alert('Pilih minimal satu item untuk checkout!');
                    return;
                }
                // Simpan data item terpilih ke localStorage/sessionStorage jika perlu
                const selectedItems = checkedIdx.map(idx => cart[idx]);
                sessionStorage.setItem('checkout_items', JSON.stringify(selectedItems));
                // Tutup modal keranjang
                document.getElementById('cartModal').style.display = 'none';
                // Tampilkan modal checkout
                openCheckoutModal();
            };



            // Tutup modal input keranjang
            document.getElementById('closeCartInputModal').onclick = function () {
                document.getElementById('cartInputModal').style.display = 'none';
            };
            document.getElementById('cartInputModal').onclick = function (e) {
                if (e.target === this) this.style.display = 'none';
            };

            // Proses submit form tambah ke keranjang
            document.getElementById('cartInputForm').onsubmit = function (e) {
                e.preventDefault();
                const id = document.getElementById('cartInputId').value;
                const nama = document.getElementById('cartInputNama').value;
                const harga = parseInt(document.getElementById('cartInputHarga').value);
                const foto = document.getElementById('cartInputFoto').value;
                const stok = parseInt(document.getElementById('cartInputStok').value);
                const qty = parseInt(document.getElementById('cartInputQty').value);
                const note = document.getElementById('cartInputNote').value;

                if (qty < 1 || qty > stok) {
                    alert('Jumlah tidak valid!');
                    return;
                }

                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                let found = cart.find(item => item.id == id && (item.note || '') === note);
                if (found) {
                    if (found.qty + qty <= stok) {
                        found.qty += qty;
                    } else {
                        alert('Stok tidak mencukupi!');
                        return;
                    }
                } else {
                    cart.push({ id, nama, harga, foto, qty, stok, note });
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                document.getElementById('cartInputModal').style.display = 'none';
                if (typeof updateCartCount === 'function') updateCartCount();
                alert('Ditambahkan ke keranjang!');
            };


            document.getElementById('openCartBtn').onclick = function () {
                renderCart();
                document.getElementById('cartModal').style.display = 'block';
            };
            document.getElementById('closeCartModal').onclick = function () {
                document.getElementById('cartModal').style.display = 'none';
            };
            document.getElementById('clearCartBtn').onclick = function () {
                localStorage.removeItem('cart');
                renderCart();
                updateCartCount();
            };
            document.getElementById('cartModal').onclick = function (e) {
                if (e.target === this) this.style.display = 'none';
            };
            updateCartCount();
        });

        function openCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('active');
            document.getElementById('checkoutForm').reset();
        }
        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('active');
        }
        document.getElementById('checkoutModal').addEventListener('click', function (e) {
            if (e.target === this) closeCheckoutModal();
        });
        document.getElementById('checkoutForm').onsubmit = function (e) {
            const jsonItems = sessionStorage.getItem('checkout_items');

            if (!jsonItems) {
                alert('Tidak ada item yang dipilih untuk checkout!');
                e.preventDefault();
                return false;
            }

            let arrItems;
            try {
                arrItems = JSON.parse(jsonItems);
            } catch (err) {
                console.error('Terjadi error JSON.parse:', err);
                alert('Terjadi kesalahan pada data keranjang.');
                e.preventDefault();
                return false;
            }

            if (!Array.isArray(arrItems) || arrItems.length === 0) {
                alert('Tidak ada item yang dipilih untuk checkout!');
                e.preventDefault();
                return false;
            }

            // Jika valid, masukkan JSON ke hidden input:
            let input = document.getElementById('checkoutItemsInput');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'items';
                input.id = 'checkoutItemsInput';
                this.appendChild(input);
            }
            input.value = jsonItems;
            return true;
            // Setelah ini, form akan mengirim data item ke logic/checkout.php
        };
    </script>

    <!-- Modal Tambah ke Keranjang -->
    <div id="cartInputModal"
        style="display:none;position:fixed;z-index:10001;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:8px;max-width:350px;margin:120px auto 0;box-shadow:0 2px 8px rgba(0,0,0,0.15);position:relative;">
            <span id="closeCartInputModal"
                style="position:absolute;top:8px;right:16px;cursor:pointer;font-size:22px;">&times;</span>
            <h3>Tambah ke Keranjang</h3>
            <form id="cartInputForm">
                <input type="hidden" id="cartInputId">
                <input type="hidden" id="cartInputNama">
                <input type="hidden" id="cartInputHarga">
                <input type="hidden" id="cartInputFoto">
                <input type="hidden" id="cartInputStok">
                <div style="margin-bottom:10px;">
                    <label>Jumlah:</label>
                    <input type="number" id="cartInputQty" min="1" value="1" style="width:60px;">
                    <span id="cartInputStokInfo" style="font-size:12px;color:#888;"></span>
                </div>
                <div style="margin-bottom:10px;">
                    <label>Catatan:</label>
                    <textarea id="cartInputNote" rows="2" style="width:100%; border-radius: 5px;"></textarea>
                </div>
                <button type="submit"
                    style="background:#6d4c2b;color:#fff;padding:8px 18px;border:none;border-radius:4px;cursor:pointer;">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Modal Checkout -->
    <div id="checkoutModal">
        <form id="checkoutForm" action="logic/checkout.php" method="post">
            <h2>Pembayaran</h2>
            <label>Nama Customer:<br>
                <input type="text" name="customer_name" required>
            </label>
            <label class="radio-group">
                <input type="radio" name="jenis_order" value="dine_in" checked>
                Dine In
                <input type="radio" name="jenis_order" value="take_away">
                Take Away
            </label>
            <label>Metode Pembayaran:<br>
                <select name="payment_method" required>
                    <option value="cash">Cash</option>
                    <option value="e-wallet">E-Wallet</option>
                    <option value="qris">QRIS</option>
                </select>
            </label>
            <input type="hidden" name="items" id="checkoutItemsInput">
            <button type="submit" class="btn-bayar">Bayar & Cetak Struk</button>
            <button type="button" class="btn-batal" onclick="closeCheckoutModal()">Batal</button>
        </form>
    </div>

</body>

</html>