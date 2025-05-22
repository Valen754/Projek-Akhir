<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Menu</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* .image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }

        .btn-overlay {
            position: absolute;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%);
            display: flex;
            gap: 16px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 2;
        }

        .image-wrapper:hover .btn-overlay {
            opacity: 1;
        }

        .btn-icon-round {
            width: 60px;
            height: 48px;
            background: transparent;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            outline: none;
            cursor: pointer;
            transition: background 0.2s;
            margin: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-icon-round svg,
        .btn-icon-round i {
            color: #fff;
            font-size: 2em;
        }

        .btn-icon-round:hover {
            background: #543310;;
        }

        .card .btn-overlay {
            pointer-events: none;
        }

        .card .btn-overlay form,
        .card .btn-overlay a {
            pointer-events: auto;
        } */
            
        .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
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
    </style> 
</head>

<body>
    <!--BAGIAN NAVBAR-->
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

    <!-- BANNER -->
    <div class="container-banner">
        <div class="overlay"></div> <!-- Overlay Gelap -->
        <div class="judul">Menu</div>
    </div>

    <!-- TAB CARD -->
    <div class="container">
        <!-- KATEGORI -->
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

<!-- PRODUK -->
<div class="tab-content">
    <div class="tab-pane active" id="semua">
        <div class="row">
            <?php
            $sql = "SELECT * FROM menu WHERE quantity > 0";
            if (isset($_GET['type']) && !empty($_GET['type'])) {
                $type = $_GET['type'];
                $sql .= " AND type = '$type'";
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
                                            <a class="btn-icon-round" href="../detail/detail.php?id=<?php echo $row['id']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </a>
                                            <?php if (isset($_SESSION['user_id'])): ?>
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



    <!-- FOOTER -->
    <?php
    include '../../views/footer.php';
    ?>

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
</body>
<script>
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
</script>

</html>