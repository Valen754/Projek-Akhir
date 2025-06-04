<?php
include '../../koneksi.php'; // path relatif ke koneksi.php dari kasir.php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Pastikan hanya pengguna dengan role 'kasir' yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'kasir') {
    header("Location: ../login/login.php"); // Arahkan ke halaman login jika role tidak sesuai
    exit();
}

// Ambil menu tersedia
$query = "SELECT * FROM menu WHERE status = 'tersedia'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Halaman Kasir</title>
    <link rel="stylesheet" href="../../css/kasir.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php $activePage = 'kasir'; ?>
            <?php include '../../views/kasir/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <main>
            <header>
                <h1>Kasir - Tapal Kuda</h1>
                <p>Silakan pilih menu untuk menambahkan ke pesanan</p>
            </header>

            <nav class="tabs">
                <button class="tab-link active">Semua</button>
                <div class="search-container">
                    <i class="fas fa-search icon-search"></i>
                    <input type="search" placeholder="Cari menu..." id="searchInput">
                </div>
            </nav>

            <section class="choose-dishes">
                <h2>Pilih Menu</h2>
                <div class="dishes-grid" id="menuGrid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="dish-card" data-name="<?= strtolower($row['nama']) ?>">
                            <img src="../../asset/<?= htmlspecialchars($row['url_foto']) ?>"
                                alt="<?= htmlspecialchars($row['nama']) ?>">
                            <h3><?= htmlspecialchars($row['nama']) ?></h3>
                            <p class="price">Rp<?= number_format($row['price'], 0, ',', '.') ?></p>
                            <p class="available">Tersedia</p>
                            <button class="add-to-order" data-id="<?= $row['id'] ?>"><i class="fas fa-plus"></i></button>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </main>

        <!-- Orders Panel -->
        <aside class="orders-panel">
            <header>
                <h2>Pesanan <span>(0 item)</span></h2>
            </header>

            <ul class="order-list" id="orderList">
                <!-- Dinamis isi keranjang -->
            </ul>

            <footer>
                <div class="subtotal">
                    <span>Subtotal</span><span id="subtotalHarga">Rp0</span>
                </div>
                <div class="discount">
                    <span style="color: white;">Diskon</span><span style="color: white;">Rp0</span>
                </div>
                <div class="discount">
                    <span style="color: white;">Pajak (10%)</span><span id="pajakHarga" style="color: white;">Rp0</span>
                </div>
                <div class="subtotal">
                    <span style="color: white;">Total</span><span id="totalHarga" style="color: white;">Rp0</span>
                </div>
                <button id="bayarBtn" style="color: white;">Bayar Sekarang</button>
            </footer>
        </aside>
    </div>

    <!-- Modal/Form Checkout (hidden by default) -->
    <div id="checkoutModal"
        style="display:none; position:fixed; left:0; top:0; right:0; bottom:0; background:rgba(30,36,50,0.85); z-index:999; justify-content:center; align-items:center;">
        <form id="checkoutForm" action="logic/checkout.php" method="post" style="background:#222b3a;padding:32px;border-radius:12px;max-width:340px;width:100%;margin:auto;box-shadow:0 2px 24px #0006;color:#fff;">
            <h2 style="margin-bottom:20px;">Pembayaran</h2>
            <label>Nama Customer (opsional):<br>
                <input type="text" name="customer_name"
                    style="width:100%;margin-bottom:12px;padding:8px;border-radius:8px;border:none;">
            </label>
            <label>
                <input type="radio" name="jenis_order" value="dine_in" checked>
                Dine In
                <br>
                <input type="radio" name="jenis_order" value="take_away">
                Take Away
                <br>
            </label>
            <label>Metode Pembayaran:<br>
                <select name="payment_method" required
                    style="width:100%;margin-bottom:18px;padding:8px;border-radius:8px;">
                    <option value="cash">Cash</option>
                    <option value="e-wallet">E-Wallet</option>
                    <option value="qris">QRIS</option>
                </select>
            </label>
            <label>Kode Voucher (jika ada):<br>
                <input type="text" name="voucher_code"
                    style="width:100%;margin-bottom:18px;padding:8px;border-radius:8px;border:none;">
            </label>
            <button type="submit"
                style="width:100%;background:#e07b6c;padding:12px 0;border:none;border-radius:10px;font-weight:700;color:#fff;">Bayar
                & Cetak Struk</button>
            <button type="button" onclick="closeModal()"
                style="width:100%;margin-top:8px;padding:10px 0;border:none;border-radius:10px;background:#222b3a;color:#e07b6c;">Batal</button>
        </form>
    </div>

        <script>
        // Pencarian menu lokal
        document.getElementById("searchInput").addEventListener("input", function () {
            const value = this.value.toLowerCase();
            document.querySelectorAll(".dish-card").forEach(card => {
                const name = card.getAttribute("data-name");
                card.style.display = name.includes(value) ? "flex" : "none";
            });
        });

        // Tambah ke keranjang
        document.querySelectorAll('.add-to-order').forEach(button => {
            button.addEventListener('click', function () {
                const menuId = this.dataset.id;
                fetch('logic/add_to_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'menu_id=' + menuId
                })
                    .then(res => res.json())
                    .then(data => {
                        updateCartUI(data);
                    });
            });
        });

        // Load cart saat halaman dibuka
        window.onload = function () {
            fetch('logic/get_cart.php')
                .then(res => res.json())
                .then(data => {
                    updateCartUI(data);
                });
        };

        function updateCartUI(data) {
            const list = document.getElementById("orderList");
            const headerText = document.querySelector(".orders-panel header h2 span");
            let total = 0;
            let html = '';
            data.forEach(item => {
                html += `
        <li>
            <img src="../../asset/${item.url_foto}" />
            <div class="order-info">
                <p class="name">${item.nama}</p>
                <p class="price">Rp${item.subtotal.toLocaleString()}</p>
                <input type="text" value="${item.item_notes || ''}" placeholder="Catatan..." data-id="${item.menu_id}" onchange="updateNote(this)">
            </div>
            <div class="order-qty-delete">
                <div class="qty-controls">
                    <button onclick="changeQty(${item.menu_id}, -1)">-</button>
                    <span class="qty">${item.quantity}</span>
                    <button onclick="changeQty(${item.menu_id}, 1)">+</button>
                </div>
                <button class="delete-btn" onclick="deleteItem(${item.menu_id})"><i class="fas fa-trash"></i></button>
            </div>
        </li>
        `;
                total += parseInt(item.subtotal);
            });

            // Pajak 10%
            const pajak = Math.round(total * 0.10);
            // Diskon (jika ada, misal 0)
            const diskon = 0;
            // Total akhir
            const totalAkhir = total - diskon + pajak;

            list.innerHTML = html;
            headerText.textContent = `(${data.length} item)`;
            document.getElementById("subtotalHarga").textContent = "Rp" + total.toLocaleString();
            document.querySelector(".discount span:last-child").textContent = "Rp" + diskon.toLocaleString();
            document.getElementById("pajakHarga").textContent = "Rp" + pajak.toLocaleString();
            document.getElementById("totalHarga").textContent = "Rp" + totalAkhir.toLocaleString();
        }

        function changeQty(id, delta) {
            fetch('logic/update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `menu_id=${id}&delta=${delta}`
            })
                .then(res => res.json())
                .then(data => updateCartUI(data));
        }

        function deleteItem(id) {
            fetch('logic/delete_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `menu_id=${id}`
            })
                .then(res => res.json())
                .then(data => updateCartUI(data));
        }

        function updateNote(input) {
            const id = input.dataset.id;
            const note = input.value;
            fetch('logic/update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `menu_id=${id}&note=${encodeURIComponent(note)}`
            });
        }

        // Tampilkan modal saat klik bayar
        document.getElementById("bayarBtn").onclick = function () {
            document.getElementById("checkoutModal").style.display = "flex";
        };
        // Tutup modal
        function closeModal() {
            document.getElementById("checkoutModal").style.display = "none";
        }

        // Submit checkout
document.getElementById("checkoutForm").onsubmit = function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('logic/checkout.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'struk.php?id=' + data.order_id;
        } else {
            alert("Transaksi gagal: " + data.message);
        }
    });
};

    </script>

</body>

</html>