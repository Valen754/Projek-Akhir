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

// Fetch all order types for radio buttons (Diperlukan untuk form checkout)
$order_types_query = mysqli_query($conn, "SELECT id, type_name FROM order_types ORDER BY type_name ASC");
$order_types = [];
while ($row = mysqli_fetch_assoc($order_types_query)) {
    $order_types[] = $row;
}

// Fetch all payment methods for dropdown (Diperlukan untuk form checkout)
$payment_methods_query = mysqli_query($conn, "SELECT id, method_name FROM payment_methods ORDER BY method_name ASC");
$payment_methods = [];
while ($row = mysqli_fetch_assoc($payment_methods_query)) {
    $payment_methods[] = $row;
}

// Ambil menu tersedia - Query diubah agar sesuai dengan status_id di tabel `menu`
$query = "SELECT m.*, ms.status_name 
          FROM menu m
          JOIN menu_status ms ON m.status_id = ms.id
          WHERE ms.status_name = 'Tersedia'"; // Filter berdasarkan status_name 'Tersedia'
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
        <div class="sidebar">
            <?php $activePage = 'kasir'; ?>
            <?php include '../../views/kasir/sidebar.php'; ?>
        </div>

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

        <aside class="orders-panel">
            <header>
                <h2>Pesanan <span id="orderItemCount">(0 item)</span></h2> </header>

            <ul class="order-list" id="orderList">
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

    <div id="checkoutModal"
        style="display:none; position:fixed; left:0; top:0; right:0; bottom:0; background:rgba(30,36,50,0.85); z-index:999; justify-content:center; align-items:center;">
        <form id="checkoutForm" action="logic/checkout.php" method="post" 
            style="background:#222b3a;padding:32px;border-radius:12px;max-width:340px;width:100%;margin:auto;box-shadow:0 2px 24px #0006;color:#fff;">
            <h2 style="margin-bottom:20px;">Pembayaran</h2>
            <label>Nama Customer (opsional):<br>
                <input type="text" name="customer_name"
                    style="width:100%;margin-bottom:12px;padding:8px;border-radius:8px;border:none;">
            </label>
            <label>Jenis Pesanan:<br>
                <?php foreach ($order_types as $type_option): ?>
                    <input type="radio" name="jenis_order" value="<?= htmlspecialchars($type_option['type_name']) ?>"
                        <?= (strtolower($type_option['type_name']) == 'dine in') ? 'checked' : '' ?>>
                    <?= htmlspecialchars($type_option['type_name']) ?><br>
                <?php endforeach; ?>
            </label>
            <label>Metode Pembayaran:<br>
                <select name="payment_method" required
                    style="width:100%;margin-bottom:18px;padding:8px;border-radius:8px;">
                    <?php foreach ($payment_methods as $method_option): ?>
                        <option value="<?= htmlspecialchars($method_option['method_name']) ?>">
                            <?= htmlspecialchars($method_option['method_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Kode Voucher (jika ada):<br>
                <input type="text" name="voucher_code"
                    style="width:100%;margin-bottom:18px;padding:8px;border-radius:8px;border:none;">
            </label>
            <input type="hidden" name="items" id="checkoutItemsInput"> 

            <button type="submit" id="submitCheckoutBtn"
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
            // Tampilkan pesan error jika ada dari redirect backend
            const urlParams = new URLSearchParams(window.location.search);
            const errorMsg = urlParams.get('error_msg');
            if (errorMsg) {
                alert("Error: " + decodeURIComponent(errorMsg));
                // Opsional: Hapus parameter error_msg dari URL setelah ditampilkan
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            fetch('logic/get_cart.php')
                .then(res => res.json())
                .then(data => {
                    updateCartUI(data);
                });
        };

        function updateCartUI(data) {
            const list = document.getElementById("orderList");
            const headerText = document.querySelector(".orders-panel header h2 span");
            const bayarBtn = document.getElementById("bayarBtn");
            const submitCheckoutBtn = document.getElementById("submitCheckoutBtn"); // Dapatkan tombol submit di dalam modal

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

            // Aktifkan/nonaktifkan tombol "Bayar Sekarang" dan tombol submit modal berdasarkan jumlah item di keranjang
            if (data.length > 0) {
                bayarBtn.disabled = false;
                bayarBtn.style.opacity = '1';
                bayarBtn.style.cursor = 'pointer';
                if (submitCheckoutBtn) { // Pastikan tombol ada sebelum mencoba mengaktifkan
                    submitCheckoutBtn.disabled = false;
                    submitCheckoutBtn.style.opacity = '1';
                    submitCheckoutBtn.style.cursor = 'pointer';
                }
            } else {
                bayarBtn.disabled = true;
                bayarBtn.style.opacity = '0.5'; // Redupkan tampilan jika nonaktif
                bayarBtn.style.cursor = 'not-allowed';
                if (submitCheckoutBtn) { // Pastikan tombol ada sebelum mencoba menonaktifkan
                    submitCheckoutBtn.disabled = true;
                    submitCheckoutBtn.style.opacity = '0.5';
                    submitCheckoutBtn.style.cursor = 'not-allowed';
                }
            }
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
            // Hanya buka jika tidak dinonaktifkan
            if (!this.disabled) {
                document.getElementById("checkoutModal").style.display = "flex";
            }
        };
        // Tutup modal
        function closeModal() {
            document.getElementById("checkoutModal").style.display = "none";
        }

        // Submit checkout: HAPUS fetch() untuk submit tradisional
        document.getElementById("checkoutForm").onsubmit = function (e) {
            // Pastikan tombol submit di dalam modal tidak dinonaktifkan sebelum melanjutkan
            const submitBtn = document.getElementById("submitCheckoutBtn");
            if (submitBtn && submitBtn.disabled) {
                alert("Transaksi tidak dapat dilakukan: Keranjang kosong atau ada masalah.");
                e.preventDefault(); // Mencegah submit tradisional juga
                return;
            }

            // Saat ini, form akan disubmit secara tradisional (bukan AJAX)
            // Jadi, tidak perlu lagi ada fetch() di sini.
            // Browser akan otomatis mengikuti redirect dari logic/checkout.php
            // Pastikan data keranjang disisipkan ke input hidden sebelum submit
            const checkoutItems = [];
            document.querySelectorAll('#orderList li').forEach(itemElement => {
                // Contoh cara mengambil data dari setiap item di orderList
                // Anda mungkin perlu menyesuaikan selector dan cara ambil datanya
                const menuId = itemElement.querySelector('.delete-btn').dataset.id; // Contoh ambil ID
                const quantity = parseInt(itemElement.querySelector('.qty').textContent);
                const priceText = itemElement.querySelector('.price').textContent;
                const price = parseInt(priceText.replace('Rp', '').replace(/\./g, ''));
                const name = itemElement.querySelector('.name').textContent;
                const notes = itemElement.querySelector('input[type="text"]').value;
                const fotoUrl = itemElement.querySelector('img').src;
                const fotoName = fotoUrl.substring(fotoUrl.lastIndexOf('/') + 1);

                checkoutItems.push({
                    id: menuId,
                    name: name,
                    price: price,
                    quantity: quantity,
                    note: notes,
                    foto: fotoName
                });
            });

            // Isi input hidden dengan data keranjang dalam format JSON
            document.getElementById('checkoutItemsInput').value = JSON.stringify(checkoutItems);

            // Biarkan form disubmit secara tradisional
            // Hapus e.preventDefault() yang ada jika ingin submit tradisional sepenuhnya
            // Jika Anda ingin menunda submit untuk validasi JavaScript lain, 
            // biarkan e.preventDefault() dan submit secara manual setelah validasi lulus.
            // Namun, karena kita ingin submit tradisional, e.preventDefault() harus dihapus.
            // Atau, hanya panggil e.preventDefault() jika validasi GAGAL.
        };

    </script>

</body>

</html>