<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Menu</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .image-wrapper {
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
                    // Query dasar
                    $sql = "SELECT * FROM menu WHERE 1=1";

                    // Filter berdasarkan kategori
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
                                        <div class="card-title">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?>
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

    <!-- Modal -->
    <!-- <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <div class="modal-header">
                <h1 class="modal-title">Order Confirmation</h1>
                <button class="close-button" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="modal-item">
                        <img src="Foto/Kopi/Real/KOPI TUBRUK ROBUSTA.jpg" width="100px" alt="Kopi Tubruk Robusta">
                        <div class="modal-item-details">
                            <p>Kopi tubruk robusta</p>
                        </div>
                        <div class="modal-item-price">
                            <button type="button" id="decrease" class="btn-adjust">&minus;</button>
                            <p id="quantity">1</p>
                            <button type="button" id="increase" class="btn-adjust">&plus;</button>
                            <p>= Rp
                            <p id="totalPrice">12,000</p>
                            </p>
                        </div>
                    </div>
                    <div class="modal-message">
                        <label for="messageText">message :</label>
                        <textarea id="messageText" placeholder="Do you have any messages?"></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-submit" style="font-family: inherit;">Send</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-primary" id="cartModal" style="font-family: inherit;">Add to
                    cart</button>
                <button type="button" class="btn-success" id="bukaModal" style="font-family: inherit;">Make
                    payment</button>
            </div>
        </div>
    </div> -->

    <!-- FOOTER -->
    <?php
    include '../../views/footer.php';
    ?>

    <script src="../../js/menu.js"></script>
</body>

</html>