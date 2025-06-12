<?php
include '../../koneksi.php'; //

// Ambil ID produk dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil detail produk
    $sql = "SELECT * FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href = '../menu/menu.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID produk tidak valid!'); window.location.href = '../menu/menu.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | <?= htmlspecialchars($product['nama']) ?></title>
    <link rel="stylesheet" href="../../css/detail.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

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

<body>
    <?php include '../../views/header.php'; // ?>

    <div class="wadah-breadcrumb">
        <nav class="navigasi-breadcrumb" aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li><a href="../menu/menu.php">Menu</a></li>
                <li><?= ucfirst(htmlspecialchars($product['type'])) ?></li>
                <li class="aktif"><?= htmlspecialchars($product['nama']) ?></li>
            </ul>
        </nav>
    </div>

    <div class="wadah-isi">
        <div class="kontainer-produk">
            <div class="kolom-gambar">
                <img src="../../asset/<?= htmlspecialchars($product['url_foto']) ?>"
                    alt="<?= htmlspecialchars($product['nama']) ?>">
                <div class="icon-container">
                </div>
            </div>

            <div class="kolom-detail">
                <h2><?= htmlspecialchars($product['nama']) ?></h2>
                <p><?= nl2br(htmlspecialchars($product['deskripsi'])) ?></p>
                <h3 id="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></h3>
                <button class="add-to-cart-btn" data-id="<?= $product['id'] ?>"
                    data-nama="<?= htmlspecialchars($product['nama']) ?>" data-harga="<?= $product['price'] ?>"
                    data-foto="<?= htmlspecialchars($product['url_foto']) ?>" data-stok="<?= $product['status'] ?>"
                    style="background:#6d4c2b;color:#fff;padding:10px 24px;border:none;border-radius:6px;font-size:16px;cursor:pointer;margin-top:12px;">
                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                </button>
            </div>
        </div>
    </div>

    <div class="wadah-komentar">
        <h3>Comments</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="form-komentar">
                <form action="logic/submit_review.php" method="POST">
                    <textarea name="comment" placeholder="Write your comment here..." required></textarea>
                    <div class="rating-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" value="<?= $i ?>" id="rate<?= $i ?>" required>
                            <label for="rate<?= $i ?>" class="bintang">&#9733;</label>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <button type="submit" class="tombol-submit">Submit</button>
                </form>
            </div>
        <?php else: ?>
            <p><a href="../../auth/login.php">Login</a> to leave a comment.</p>
        <?php endif; ?>

        <div class="list-komentar">
            <?php
            // Perubahan pada query SQL untuk mengambil profile_picture
            $sql = "SELECT r.comment, r.rating, r.created_at, u.username, u.profile_picture
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.menu_id = ?
                ORDER BY r.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()):
                // Path foto profil user
                $profile_pic_path = !empty($row['profile_picture'])
                    ? '../../asset/user_picture/' . htmlspecialchars($row['profile_picture'])
                    : '../../asset/user_picture/default-avatar.png';
                ?>
                <div class="komentar-item">
                    <div class="komentar-header">
                        <img src="<?= $profile_pic_path ?>" alt="Foto Profil" class="foto-profil">
                        <h4><?= htmlspecialchars($row['username']) ?>
                            <span class="waktu-komentar"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                        </h4>
                        <div class="rating">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $row['rating'] ? '<span class="bintang">&#9733;</span>' : '<span class="bintang">&#9734;</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <p><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="wadah-rekomendasi">
        <h3>Rekomendasi untuk Anda</h3>
        <div class="baris-rekomendasi">
            <?php
            // Mengambil 4 rekomendasi acak dari database
            $sql_rekomendasi = "SELECT id, nama, url_foto FROM menu ORDER BY RAND() LIMIT 4";
            $result_rekomendasi = $conn->query($sql_rekomendasi);

            if ($result_rekomendasi->num_rows > 0) {
                while ($rekomendasi = $result_rekomendasi->fetch_assoc()):
                    ?>
                    <div class="kolom-rekomendasi">
                        <a href="detail.php?id=<?= htmlspecialchars($rekomendasi['id']) ?>">
                            <img src="../../asset/<?= htmlspecialchars($rekomendasi['url_foto']) ?>"
                                alt="<?= htmlspecialchars($rekomendasi['nama']) ?>">
                        </a>
                    </div>
                    <?php
                endwhile;
            } else {
                echo "<p>Tidak ada rekomendasi.</p>";
            }
            ?>
        </div>
    </div>

    <?php include '../../views/footer.php'; // ?>

    <!-- Tombol Buka Keranjang -->
    <button id="openCartBtn"
        style="position:fixed;bottom:32px;right:32px;z-index:999;background:#6d4c2b;color:#fff;padding:12px 20px;border:none;border-radius:50px;box-shadow:0 2px 8px rgba(0,0,0,0.15);font-size:18px;">
        <i class="fas fa-shopping-cart"></i> <span id="cartCount" style="color: white;">0</span>
    </button>

    <!-- Modal Keranjang -->
    <div id="cartModal"
        style="display:none;position:fixed;z-index:10000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:12px;max-width:450px;margin:80px auto 0;box-shadow:0 8px 16px rgba(0,0,0,0.2);position:relative;display:flex;flex-direction:column;justify-content:space-between;height:80vh;">
            <span id="closeCartModal"
                style="position:absolute;top:16px;right:16px;cursor:pointer;font-size:28px;color:#333;font-weight:bold;">&times;</span>
            <h3 style="font-size:24px;color:#2c3e50;text-align:center;margin-bottom:20px;">Keranjang Belanja</h3>
            <div id="cartItems" style="flex:1;overflow-y:auto;margin-bottom:16px;"></div>
            <div id="cartSubtotal"
                style="font-size:16px;color:#2c3e50;font-weight:normal;margin-top:8px;text-align:center;"></div>
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

        <!-- Modal Tambah ke Keranjang -->
    <div id="cartInputModal"
        style="display:none;position:fixed;z-index:10001;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2); color: black;">
        <div
            style="color: black; background:#fff;padding:24px 32px;border-radius:8px;max-width:350px;margin:120px auto 0;box-shadow:0 2px 8px rgba(0,0,0,0.15);position:relative;">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Modal Tambah ke Keranjang ---
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const { id, nama, harga, foto, stok } = this.dataset;
                    document.getElementById('cartInputId').value = id;
                    document.getElementById('cartInputNama').value = nama;
                    document.getElementById('cartInputHarga').value = harga;
                    document.getElementById('cartInputFoto').value = foto;
                    document.getElementById('cartInputStok').value = stok;
                    document.getElementById('cartInputQty').value = "1";
                    document.getElementById('cartInputQty').max = stok;
                    document.getElementById('cartInputStokInfo').textContent = `(Stok: ${stok})`;
                    document.getElementById('cartInputNote').value = '';
                    document.getElementById('cartInputModal').style.display = 'block';
                });
            });
            document.getElementById('closeCartInputModal').addEventListener('click', () => {
                document.getElementById('cartInputModal').style.display = 'none';
            });
            // --- Cart Logic ---
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
                    if (cartCount) cartCount.textContent = count;
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
                try {
                    const cart = loadCart();
                    const cartItemsDiv = document.getElementById('cartItems');
                    const cartSubtotal = document.getElementById('cartSubtotal');
                    const cartTax = document.getElementById('cartTax');
                    const cartTotal = document.getElementById('cartTotal');
                    const checkoutBtn = document.getElementById('checkoutBtn');
                    if (!cartItemsDiv || !cartTotal || !checkoutBtn) return;
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
                            <span>Rp ${parseInt(item.harga).toLocaleString('id-ID')} x ${item.qty}</span>
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
            // Add to cart form
            const cartForm = document.getElementById('cartInputForm');
            if (cartForm) {
                cartForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    try {
                        const id = document.getElementById('cartInputId').value;
                        const nama = document.getElementById('cartInputNama').value;
                        const harga = parseInt(document.getElementById('cartInputHarga').value);
                        const foto = document.getElementById('cartInputFoto').value;
                        const stok = parseInt(document.getElementById('cartInputStok').value);
                        const qty = parseInt(document.getElementById('cartInputQty').value);
                        const note = document.getElementById('cartInputNote').value;
                        if (!id || !nama || !harga || !foto || !stok) {
                            throw new Error('Data menu tidak lengkap');
                        }
                        if (qty < 1 || qty > stok) {
                            alert('Jumlah tidak valid!');
                            return;
                        }
                        const cart = loadCart();
                        let found = cart.find(item => item.id === id && (item.note || '') === note);
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
            // Cart button handlers
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
                        if (item.qty > item.stok) {
                            alert(`Stok untuk ${item.nama} tidak mencukupi. Tersedia: ${item.stok}`);
                            return null;
                        }
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
                    localStorage.removeItem('cart');
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
    
</body>

</html>