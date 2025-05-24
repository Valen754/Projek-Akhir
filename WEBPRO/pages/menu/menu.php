<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Menu</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            max-width: 90%;
        }

        /* Style untuk tombol favorit */
        .btn-favorite {
            color: white; /* Warna default untuk ikon favorit */
            cursor: pointer;
            transition: color 0.2s ease-in-out;
            background: transparent;
            border: none;
            font-size: 28px; /* Sesuaikan ukuran sesuai kebutuhan */
        }

        .btn-favorite.active {
            color: red; /* Warna saat difavoritkan */
        }
        .favorite-wrapper {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3; /* Pastikan di atas tombol overlay */
        }
        .favorite-wrapper .favorite-icon {
            font-size: 2em; /* Sesuaikan ukuran sesuai kebutuhan */
            color: #ccc; /* Warna default */
            cursor: pointer;
            transition: color 0.2s;
        }
        .favorite-wrapper .favorite-icon.favorited {
            color: red; /* Warna saat difavoritkan */
        }
    </style>
</head>

<body>
    <?php
    include '../../views/header.php';
    include '../../koneksi.php'; // Koneksi ke database
    
    // Query untuk menghitung jumlah menu berdasarkan kategori
    $countQuery = "SELECT type, COUNT(*) as total FROM menu GROUP BY type";
    $countResult = $conn->query($countQuery);

    $menuCounts = [];
    if ($countResult->num_rows > 0) {
        while ($countRow = $countResult->fetch_assoc()) {
            $menuCounts[$countRow['type']] = $countRow['total'];
        }
    }
    ?>

    <div class="container-banner">
        <div class="overlay"></div> <div class="judul">Menu</div>
    </div>

    <div class="container">
        <ul class="nav-pills">
            <div class="kategori">KATEGORI PRODUK</div>
            <li>
                <a class="nav-link <?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>"
                    href="menu.php">
                    Semua <span>(<?php echo array_sum($menuCounts); ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'kopi') ? 'active' : ''; ?>"
                    href="menu.php?type=kopi">
                    Kopi <span>(<?php echo isset($menuCounts['kopi']) ? $menuCounts['kopi'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>"
                    href="menu.php?type=minuman">
                    Non Kopi <span>(<?php echo isset($menuCounts['minuman']) ? $menuCounts['minuman'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>"
                    href="menu.php?type=makanan_berat">
                    Makanan
                    <span>(<?php echo isset($menuCounts['makanan_berat']) ? $menuCounts['makanan_berat'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>"
                    href="menu.php?type=cemilan">
                    Cemilan <span>(<?php echo isset($menuCounts['cemilan']) ? $menuCounts['cemilan'] : 0; ?>)</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="semua">
                <div class="row">
                    <?php
                    $sql = "SELECT m.*, 
                                  CASE WHEN f.menu_id IS NOT NULL THEN 1 ELSE 0 END AS is_favorited
                            FROM menu m";
                    
                    // Tambahkan kondisi WHERE untuk jenis jika ada
                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        $type = $conn->real_escape_string($_GET['type']);
                        $sql .= " WHERE m.type = '$type'";
                    }

                    // Tambahkan kondisi untuk kuantitas dan status
                    if (strpos($sql, 'WHERE') !== false) {
                        $sql .= " AND m.quantity > 0 AND m.status = 'tersedia'";
                    } else {
                        $sql .= " WHERE m.quantity > 0 AND m.status = 'tersedia'";
                    }

                    // Tambahkan LEFT JOIN untuk favorites jika user_id ada di sesi
                    if (isset($_SESSION['user_id'])) {
                        $user_id_for_fav = $_SESSION['user_id'];
                        $sql = "SELECT m.*, 
                                  CASE WHEN f.menu_id IS NOT NULL THEN 1 ELSE 0 END AS is_favorited
                                FROM menu m
                                LEFT JOIN favorites f ON m.id = f.menu_id AND f.user_id = $user_id_for_fav";
                        
                        // Ulangi kondisi WHERE setelah join agar tidak hilang
                        if (isset($_GET['type']) && !empty($_GET['type'])) {
                            $type = $conn->real_escape_string($_GET['type']);
                            $sql .= " WHERE m.type = '$type'";
                        }
                        if (strpos($sql, 'WHERE') !== false) {
                            $sql .= " AND m.quantity > 0 AND m.status = 'tersedia'";
                        } else {
                            $sql .= " WHERE m.quantity > 0 AND m.status = 'tersedia'";
                        }
                    } else {
                        // Jika user tidak login, is_favorited selalu 0
                        $sql = "SELECT m.*, 0 AS is_favorited FROM menu m";
                         // Ulangi kondisi WHERE agar tidak hilang
                        if (isset($_GET['type']) && !empty($_GET['type'])) {
                            $type = $conn->real_escape_string($_GET['type']);
                            $sql .= " WHERE m.type = '$type'";
                        }
                        if (strpos($sql, 'WHERE') !== false) {
                            $sql .= " AND m.quantity > 0 AND m.status = 'tersedia'";
                        } else {
                            $sql .= " WHERE m.quantity > 0 AND m.status = 'tersedia'";
                        }
                    }

                    $result = $conn->query($sql);


                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="col">
                                <div class="card">
                                    <div class="image-wrapper">
                                        <img src="../../asset/<?php echo $row['url_foto']; ?>"
                                            alt="<?php echo $row['nama']; ?>">
                                        <div class="btn-overlay">
                                            <a class="btn-icon-round"
                                                href="../detail/detail.php?id=<?php echo $row['id']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </a>
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                <form class="add-to-cart-form" method="POST"
                                                    action="../keranjang/logic/add_keranjang.php" style="display:inline;">
                                                    <input type="hidden" name="menu_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <input type="hidden" name="catatan" value="">
                                                    <button type="submit" class="btn-icon-round add-cart-button">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                            fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                                                            <path
                                                                d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <a href="../login/login.php" class="btn-icon-round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                                                        <path
                                                            d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="favorite-wrapper">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                      <svg xmlns="http://www.w3.org/2000/svg"
     width="16"
     height="16"
     fill="<?php echo $row['is_favorited'] ? 'red' : 'currentColor'; ?>"
     class="bi bi-heart btn-favorite <?php echo $row['is_favorited'] ? 'active' : ''; ?>"
     data-menu-id="<?php echo $row['id']; ?>"
     viewBox="0 0 16 16"
     style="cursor: pointer;">
  <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
</svg>


                                    <?php else: ?>
                                        <a href="../login/login.php" class="btn-favorite">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-title"><?php echo $row['nama']; ?></div>
                                        <div class="card-title">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></div>
                                        <div class="card-title" style="color:#6d4c2b;">
                                            Tersedia: <?php echo $row['quantity']; ?>
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
            <form action="../keranjang/logic/add_keranjang.php" method="post">
                <input type="hidden" name="menu_id" id="modalMenuId">
                <label for="modalQuantityInput">Jumlah:</label>
                <input type="number" name="quantity" id="modalQuantityInput" min="1" value="1">
                <br>
                <label for="modalCatatan">Catatan:</label>
                <input type="text" name="catatan" id="modalCatatan" placeholder="Contoh: tanpa gula">
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
        // jQuery untuk dropdowns (jika menggunakan)
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

        // Fungsionalitas tombol favorit
        document.querySelectorAll('.btn-favorite').forEach(button => {
            button.addEventListener('click', function() {
                const menuId = this.dataset.menuId;
                const isFavorited = this.classList.contains('active');
                const favoriteButton = this; // Simpan referensi ke tombol

                // Jika tidak login, arahkan ke halaman login
                <?php if (!isset($_SESSION['user_id'])): ?>
                    window.location.href = '../login/login.php';
                    return;
                <?php endif; ?>

                fetch('logic/toggle_favorite.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `menu_id=${menuId}&action=${isFavorited ? 'hapus' : 'tambah'}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'ditambahkan') {
                            favoriteButton.classList.add('active');
                            alert('Item berhasil ditambahkan ke favorit!');
                        } else {
                            favoriteButton.classList.remove('active');
                            alert('Item berhasil dihapus dari favorit!');
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui favorit.');
                });
            });
        });

        // Fungsionalitas modal (tetap sama seperti sebelumnya)
        document.querySelectorAll('.add-cart-button').forEach(button => {
            button.addEventListener('click', function(event) {
                // Mencegah pengiriman formulir default untuk ditangani melalui fetch atau menyesuaikan tindakan modal
                event.preventDefault(); 
                
                const form = this.closest('form');
                document.getElementById('modalOverlay').style.display = 'block';
                document.getElementById('modalImage').src = form.closest('.card').querySelector('img').src;
                document.getElementById('modalName').textContent = form.closest('.card').querySelector('.card-title').textContent;
                document.getElementById('modalPrice').textContent = form.closest('.card').querySelector('.card-title:nth-of-type(2)').textContent.replace('Rp ', '');
                document.getElementById('modalStok').textContent = form.closest('.card').querySelector('.card-title:nth-of-type(3)').textContent.replace('Tersedia: ', '');
                document.getElementById('modalMenuId').value = form.querySelector('input[name="menu_id"]').value;
                document.getElementById('modalQuantityInput').value = 1; // Default ke 1
                document.getElementById('modalCatatan').value = ''; // Hapus catatan
            });
        });

        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('modalOverlay').style.display = 'none';
        });

        document.getElementById('modalQuantityInput').addEventListener('input', function() {
            const currentStock = parseInt(document.getElementById('modalStok').textContent);
            if (this.value > currentStock) {
                this.value = currentStock;
                alert('Jumlah tidak boleh melebihi stok yang tersedia!');
            }
            if (this.value < 1) {
                this.value = 1;
            }
        });
    </script>
</body>

</html>