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

</head>

<body>
    <?php
    include '../../views/header.php';
    include '../../koneksi.php'; // Koneksi ke database
    
    // Dapatkan ID user yang login (jika ada)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

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
        $query_favorites = "SELECT menu_id FROM favorites WHERE user_id = " . $user_id;
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
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="semua">
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM menu WHERE quantity > 0";
                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        $type = $conn->real_escape_string($_GET['type']); // Sanitasi input
                        $sql .= " AND type = '$type'";
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
                                        <div class="btn-overlay">
                                            <a class="btn-icon-round" href="../detail/detail.php?id=<?php echo $row['id']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </a>
                                            <?php if ($user_id): ?>
                                                <form method="POST" action="../keranjang/logic/add_keranjang.php"
                                                    style="display:inline;">
                                                    <input type="hidden" name="menu_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn-icon-round" id="openModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                            fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                                                            <path
                                                                d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <a href="../login/login.php" class="btn-icon-round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        fill="currentColor" class="bi bi-bookmark-heart" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4.41c1.387-1.425 4.854 1.07 0 4.277C3.146 5.48 6.613 2.986 8 4.412z" />
                                                        <path
                                                            d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                                                    </svg>
                                                </a>
                                            <?php else: ?>
                                                <a href="../login/login.php" class="btn-icon-round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                                                        <path
                                                            d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                                                    </svg>
                                                </a>
                                                <a href="../login/login.php" class="btn-icon-round favorite-btn">
                                                    <i class="far fa-heart"></i>
                                                </a>
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

    <div id="modalOverlay" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span id="closeModal" style="cursor: pointer;">&times;</span>
            <img id="modalImage" src="" alt="Foto Produk" style="width: 200px;">
            <h3 id="modalName"></h3>
            <p>Harga: Rp <span id="modalPrice"></span></p>
            <p>Stok tersedia: <span id="modalStok"></span></p>
            <form action="proses/tambah_keranjang.php" method="post">
                <input type="hidden" name="menu_id" id="modalMenuId">
                <label for="quantity">Jumlah:</label>
                <input type="number" name="quantity" id="modalQuantityInput" min="1" value="1">
                <br>
                <label for="catatan">Catatan:</label>
                <input type="text" name="catatan" placeholder="Contoh: tanpa gula">
                <br>
                <button type="submit">Masukkan ke Keranjang</button>
            </form>
        </div>
    </div>

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
    </script>
    <script>
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
                                    // alert(data.message); // Opsional: tampilkan notifikasi
                                } else if (data.action === 'removed') {
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                    // alert(data.message); // Opsional: tampilkan notifikasi
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
    </script>
</body>

</html>