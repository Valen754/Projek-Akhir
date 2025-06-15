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
        /* Checkout styles moved to checkout.php */

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

        #qrisImageContainer {
            display: none;
            text-align: center;
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }

        #qrisImageContainer img {
            max-width: 200px;
            margin: 10px auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        #qrisImageContainer .qris-text {
            color: #fff;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <?php
    // Query untuk menghitung jumlah menu berdasarkan kategori - Updated to join with menu_types
    $countQuery = "SELECT mt.type_name AS type, COUNT(m.id) as total 
                   FROM menu m 
                   JOIN menu_types mt ON m.type_id = mt.id 
                   GROUP BY mt.type_name";
    $countResult = $conn->query($countQuery);

    $menuCounts = [];
    $totalAllMenus = 0;
    if ($countResult->num_rows > 0) {
        while ($countRow = $countResult->fetch_assoc()) {
            $menuCounts[$countRow['type']] = $countRow['total'];
            $totalAllMenus += $countRow['total'];
        }
    }

    // Ambil daftar menu yang difavoritkan oleh user yang sedang login
    $favorite_menus = [];
    if ($user_id) {
        $query_favorites = "SELECT menu_id FROM favorites WHERE user_id = ?";
        $stmt_favorites = $conn->prepare($query_favorites);
        $stmt_favorites->bind_param("i", $user_id);
        $stmt_favorites->execute();
        $result_favorites = $stmt_favorites->get_result();
        if ($result_favorites) {
            while ($row_fav = $result_favorites->fetch_assoc()) {
                $favorite_menus[] = $row_fav['menu_id'];
            }
        }
        $stmt_favorites->close();
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
                    All <span>(<?php echo $totalAllMenus; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'Kopi') ? 'active' : ''; ?>"
                    href="menu.php?type=Kopi">
                    Coffee <span>(<?php echo isset($menuCounts['Kopi']) ? $menuCounts['Kopi'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'Minuman') ? 'active' : ''; ?>"
                    href="menu.php?type=Minuman">
                    Non Coffee <span>(<?php echo isset($menuCounts['Minuman']) ? $menuCounts['Minuman'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'Makanan_Berat') ? 'active' : ''; ?>"
                    href="menu.php?type=Makanan_Berat">
                    Foods
                    <span>(<?php echo isset($menuCounts['Makanan_Berat']) ? $menuCounts['Makanan_Berat'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'Cemilan') ? 'active' : ''; ?>"
                    href="menu.php?type=Cemilan">
                    Snacks <span>(<?php echo isset($menuCounts['Cemilan']) ? $menuCounts['Cemilan'] : 0; ?>)</span>
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
                    // Mengubah query untuk menggunakan kolom status_name dan type_name
                    $sql_where = "WHERE ms.status_name = 'Tersedia'"; // Initial filter for available items

                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        $type_filter = $conn->real_escape_string($_GET['type']); // Sanitasi input

                        if ($type_filter === 'favorit' && !empty($favorite_menus)) {
                            $menu_ids = implode(',', $favorite_menus);
                            $sql = "SELECT m.*, mt.type_name, ms.status_name 
                                    FROM menu m 
                                    JOIN menu_types mt ON m.type_id = mt.id
                                    JOIN menu_status ms ON m.status_id = ms.id
                                    WHERE m.id IN ($menu_ids) AND ms.status_name = 'Tersedia'";
                        } elseif ($type_filter !== 'favorit') {
                            $sql = "SELECT m.*, mt.type_name, ms.status_name 
                                    FROM menu m 
                                    JOIN menu_types mt ON m.type_id = mt.id
                                    JOIN menu_status ms ON m.status_id = ms.id
                                    WHERE mt.type_name = '$type_filter' AND ms.status_name = 'Tersedia'";
                        } else {
                            // If 'favorit' is selected but no favorites, or invalid type, show nothing.
                            $sql = "SELECT m.*, mt.type_name, ms.status_name FROM menu m JOIN menu_types mt ON m.type_id = mt.id JOIN menu_status ms ON m.status_id = ms.id WHERE 0"; // Empty result
                        }
                    } else {
                        // Default query for "All" category
                        $sql = "SELECT m.*, mt.type_name, ms.status_name 
                                FROM menu m 
                                JOIN menu_types mt ON m.type_id = mt.id
                                JOIN menu_status ms ON m.status_id = ms.id
                                WHERE ms.status_name = 'Tersedia'";
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
                                            <a class="btn-icon-round" href="../detail/detail.php?id=<?php echo $row['id']; ?>"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </a>
                                            <button class="add-to-cart-btn btn-icon-round" data-id="<?php echo $row['id']; ?>"
                                                data-nama="<?php echo htmlspecialchars($row['nama']); ?>"
                                                data-harga="<?php echo $row['price']; ?>"
                                                data-foto="<?php echo htmlspecialchars($row['url_foto']); ?>"
                                                data-status="<?php echo htmlspecialchars($row['status_name']); ?>" title="Tambah ke Keranjang">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
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
                                            <?php echo htmlspecialchars($row['status_name']); ?> </div>
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

        <button id="openCartBtn"
            style="position:fixed;bottom:32px;right:32px;z-index:999;background:#6d4c2b;color:#fff;padding:12px 20px;border:none;border-radius:50px;box-shadow:0 2px 8px rgba(0,0,0,0.15);font-size:18px;">
            <i class="fas fa-shopping-cart"></i> <span id="cartCount" style="color: white;">0</span>
        </button>

        <div id="cartModal"
            style="display:none;position:fixed;z-index:10000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);">
            <div
                style="background:#fff;padding:24px 32px;border-radius:12px;max-width:450px;margin:80px auto 0;box-shadow:0 8px 16px rgba(0,0,0,0.2);position:relative;display:flex;flex-direction:column;justify-content:space-between;height:80vh;">

                <span id="closeCartModal"
                    style="position:absolute;top:16px;right:16px;cursor:pointer;font-size:28px;color:#333;font-weight:bold;">&times;</span>

                <h3 style="font-size:24px;color:#2c3e50;text-align:center;margin-bottom:20px;">Keranjang Belanja</h3>

                <div id="cartItems" style="flex:1;overflow-y:auto;margin-bottom:16px;">
                </div>

                <div id="cartSubtotal"
                    style="font-size:16px;color:#2c3e50;font-weight:normal;margin-top:8px;text-align:center;">
                </div>
                <div id="cartTax" style="font-size:16px;color:#2c3e50;font-weight:normal;margin-top:8px;text-align:center;">
                </div>
                <div id="cartTotal" style="font-size:18px;color:#2c3e50;font-weight:bold;margin-top:8px;text-align:center;">
                </div>

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
            }); document.addEventListener('DOMContentLoaded', function () {
                // Initialize cart functionality
                const loadCart = function () {
                    try {
                        const cart = localStorage.getItem('cart');
                        return cart ? JSON.parse(cart) : [];
                    } catch (e) {
                        console.error('Error loading cart:', e);
                        return [];
                    }
                };

                const saveCart = function (cart) {
                    try {
                        localStorage.setItem('cart', JSON.stringify(cart));
                    } catch (e) {
                        console.error('Error saving cart:', e);
                        alert('Gagal menyimpan keranjang. Mohon coba lagi.');
                    }
                };

                const updateCartCount = function () {
                    try {
                        const cart = loadCart();
                        const count = cart.reduce((a, b) => a + (b.qty || 0), 0);
                        const cartCount = document.getElementById('cartCount');
                        if (cartCount) {
                            cartCount.textContent = count;
                        }
                    } catch (e) {
                        console.error('Error updating cart count:', e);
                    }
                };

                const updateCartTotal = function () {
                    const cart = loadCart();
                    let subtotal = 0;
                    document.querySelectorAll('.cart-check').forEach((cb, idx) => {
                        if (cb.checked) {
                            subtotal += cart[idx].qty * cart[idx].harga;
                        }
                    });
                    const tax = Math.round(subtotal * 0.10);
                    const total = subtotal + tax;

                    const cartSubtotal = document.getElementById('cartSubtotal');
                    const cartTax = document.getElementById('cartTax');
                    const cartTotal = document.getElementById('cartTotal');

                    if (cartSubtotal && cartTax && cartTotal) {
                        cartSubtotal.textContent = 'Subtotal: Rp ' + subtotal.toLocaleString('id-ID');
                        cartTax.textContent = 'Pajak (10%): Rp ' + tax.toLocaleString('id-ID');
                        cartTotal.textContent = 'Total: Rp ' + total.toLocaleString('id-ID');
                    }
                };

                const renderCart = function () {
                    console.log('Rendering cart...'); // Debug log
                    try {
                        const cart = loadCart();
                        console.log('Cart contents:', cart); // Debug log

                        const cartItemsDiv = document.getElementById('cartItems');
                        const cartSubtotal = document.getElementById('cartSubtotal');
                        const cartTax = document.getElementById('cartTax');
                        const cartTotal = document.getElementById('cartTotal');
                        const checkoutBtn = document.getElementById('checkoutBtn');

                        if (!cartItemsDiv || !cartTotal || !checkoutBtn) {
                            console.error('Cart elements not found');
                            return;
                        }

                        if (cart.length === 0) {
                            cartItemsDiv.innerHTML = '<p style="text-align:center;font-size:18px;color:#888;">Keranjang kosong.</p>';
                            cartSubtotal.textContent = '';
                            cartTax.textContent = '';
                            cartTotal.textContent = '';
                            checkoutBtn.style.display = 'none';
                            return;
                        }

                        let html = '';
                        cart.forEach((item, idx) => {
                            html += `
                                <div class="cart-item">
                                    <input type="checkbox" class="cart-check" data-idx="${idx}" checked style="width:20px;height:20px;">
                                    <img src="../../asset/${item.foto}" alt="${item.nama}">
                                    <div class="item-info">
                                        <strong>${item.nama}</strong><br>
                                        <span>Rp ${item.harga.toLocaleString('id-ID')} x ${item.qty}</span>
                                        ${item.note ? `<div class="item-note">Catatan: ${item.note}</div>` : ''}
                                    </div>
                                    <div class="item-actions">
                                        <button type="button" onclick="removeCartItem(${idx})">Hapus</button>
                                    </div>
                                </div>
                            `;
                        });
                        cartItemsDiv.innerHTML = html;
                        updateCartTotal();
                        document.getElementById('checkoutBtn').style.display = '';
                    } catch (e) {
                        console.error('Error rendering cart:', e);
                    }
                };

                // Add to cart button click handlers
                document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const { id, nama, harga, foto, status, note } = this.dataset; // Changed 'stok' to 'status'

                        // Update modal inputs
                        document.getElementById('cartInputId').value = id;
                        document.getElementById('cartInputNama').value = nama;
                        document.getElementById('cartInputHarga').value = harga;
                        document.getElementById('cartInputFoto').value = foto;
                        document.getElementById('cartInputStok').value = status; // Pass status instead of numerical stock
                        document.getElementById('cartInputQty').value = "1";
                        // Removed cartInputQty.max = stok; as stock is not numerical
                        document.getElementById('cartInputStokInfo').textContent = `(Status: ${status})`; // Display status text
                        document.getElementById('cartInputNote').value = note || '';

                        // Disable quantity input and submit if status is 'habis'
                        const cartInputQty = document.getElementById('cartInputQty');
                        const cartInputFormSubmit = document.querySelector('#cartInputForm button[type="submit"]');

                        if (status.toLowerCase() === 'habis') {
                            cartInputQty.value = "0";
                            cartInputQty.setAttribute('readonly', true);
                            cartInputStokInfo.style.color = 'red';
                            cartInputFormSubmit.disabled = true;
                        } else {
                            cartInputQty.removeAttribute('readonly');
                            cartInputStokInfo.style.color = '#888';
                            cartInputFormSubmit.disabled = false;
                        }


                        // Show modal
                        document.getElementById('cartInputModal').style.display = 'block';
                    });
                });

                // Cart form submission
                const cartForm = document.getElementById('cartInputForm');
                if (cartForm) {
                    cartForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        try {
                            const id = document.getElementById('cartInputId').value;
                            const nama = document.getElementById('cartInputNama').value;
                            const harga = parseInt(document.getElementById('cartInputHarga').value);
                            const foto = document.getElementById('cartInputFoto').value;
                            const status = document.getElementById('cartInputStok').value; // Get status from input
                            const qty = parseInt(document.getElementById('cartInputQty').value);
                            const note = document.getElementById('cartInputNote').value;

                            if (!id || !nama || !harga || !foto || !status) {
                                throw new Error('Data menu tidak lengkap');
                            }

                            if (status.toLowerCase() === 'habis') { // Check status for adding to cart
                                alert('Menu ini sudah habis dan tidak bisa ditambahkan ke keranjang.');
                                return;
                            }

                            if (qty < 1) {
                                alert('Jumlah tidak valid! Minimal 1.');
                                return;
                            }

                            const cart = loadCart();
                            let found = cart.find(item => item.id === id && (item.note || '') === note);

                            if (found) {
                                // Since 'stok' is not a numeric quantity from DB, assume always add if 'tersedia'
                                found.qty += qty;
                            } else {
                                cart.push({ id, nama, harga, foto, qty, note }); // 'stok' property removed from cart item
                            }

                            saveCart(cart);
                            document.getElementById('cartInputModal').style.display = 'none';
                            updateCartCount();
                            alert('Ditambahkan ke keranjang!');
                        } catch (error) {
                            console.error('Error adding to cart:', error);
                            alert('Gagal menambahkan ke keranjang. Mohon coba lagi.');
                        }
                    });
                }

                // close button for cartInputModal
                const closeCartInputModal = document.getElementById('closeCartInputModal');
                if (closeCartInputModal) {
                    closeCartInputModal.addEventListener('click', function () {
                        document.getElementById('cartInputModal').style.display = 'none';
                    });
                }

                // Initialize cart button handlers
                const openCartBtn = document.getElementById('openCartBtn');
                const cartModal = document.getElementById('cartModal');
                const closeCartModal = document.getElementById('closeCartModal');
                const clearCartBtn = document.getElementById('clearCartBtn');
                const cartItems = document.getElementById('cartItems');

                if (openCartBtn) {
                    openCartBtn.addEventListener('click', function () {
                        renderCart();
                        cartModal.style.display = 'block';
                    });
                }

                if (closeCartModal) {
                    closeCartModal.addEventListener('click', function () {
                        cartModal.style.display = 'none';
                    });
                }

                if (clearCartBtn) {
                    clearCartBtn.addEventListener('click', function () {
                        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                            localStorage.removeItem('cart');
                            renderCart();
                            updateCartCount();
                        }
                    });
                }

                if (cartModal) {
                    cartModal.addEventListener('click', function (e) {
                        if (e.target === this) {
                            this.style.display = 'none';
                        }
                    });
                }

                if (cartItems) {
                    cartItems.addEventListener('change', function (e) {
                        if (e.target.classList.contains('cart-check')) {
                            updateCartTotal();
                        }
                    });
                }

                // Handle checkout button
                const checkoutBtn = document.getElementById('checkoutBtn');
                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', function () {
                        const cart = loadCart();
                        const checkedIdx = [];
                        document.querySelectorAll('.cart-check').forEach((cb, idx) => {
                            if (cb.checked) checkedIdx.push(idx);
                        });

                        if (checkedIdx.length === 0) {
                            alert('Pilih minimal satu item untuk checkout!');
                            return;
                        }

                        const selectedItems = checkedIdx.map(idx => {
                            const item = cart[idx];
                            // No numeric stock validation needed here based on DB schema.
                            return {
                                id: item.id,
                                name: item.nama,
                                price: item.harga,
                                quantity: item.qty,
                                note: item.note || '',
                                foto: item.foto
                            };
                        });

                        if (selectedItems.includes(null)) {
                            return;
                        }

                        sessionStorage.setItem('checkout_items', JSON.stringify(selectedItems));
                        localStorage.removeItem('cart'); // Hapus keranjang setelah checkout
                        window.location.href = 'checkout.php';
                    });
                }

                // Initialize cart count on page load
                updateCartCount();

                // Make removeCartItem function global
                window.removeCartItem = function (idx) {
                    try {
                        let cart = loadCart();
                        cart.splice(idx, 1);
                        saveCart(cart);
                        renderCart();
                        updateCartCount();
                    } catch (error) {
                        console.error('Error removing item:', error);
                        alert('Gagal menghapus item dari keranjang. Mohon coba lagi.');
                    }
                };
            });
        </script>

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
                    <input type="hidden" id="cartInputStok"> <div style="margin-bottom:10px;">
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
        </div> </body>

</html>