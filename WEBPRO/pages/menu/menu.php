<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Menu</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!--BAGIAN NAVBAR-->
    <?php
    include '../../views/header.php';
    include '../../koneksi.php'; // Koneksi ke database
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
            <li><a class="nav-link active" data-tab="semua">All<span>27</span></a></li>
            <li><a class="nav-link" data-tab="kopi">Coffe<span>11</span></a></li>
            <li><a class="nav-link" data-tab="nonkopi">Non Coffe<span>5</span></a></li>
            <li><a class="nav-link" data-tab="makanan">Foods<span>5</span></a></li>
            <li><a class="nav-link" data-tab="cemilan">Snacks<span>6</span></a></li>
            <div class="pilter">FILTER BY PRICE</div>
            <div class="price-filter">
                <!-- Range slider dengan dua pointer -->
                <div class="slide-control">
                    <input id="min-price" type="range" min="0" max="27000" step="500" value="0" />
                    <input id="max-price" type="range" min="0" max="27000" step="500" value="27000" />
                </div>
                <!-- Container untuk tombol dan teks harga -->
                <div class="filter-container">
                    <button class="btn-filter" id="filter-btn">FILTER</button>
                    <div class="price--" id="price-value">Price: Rp0 - Rp27.000</div>
                </div>
            </div>
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

                    // Filter berdasarkan harga minimum
                    if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
                        $min_price = $_GET['min_price'];
                        $sql .= " AND price >= $min_price";
                    }

                    // Filter berdasarkan harga maksimum
                    if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
                        $max_price = $_GET['max_price'];
                        $sql .= " AND price <= $max_price";
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
                                            <div class="btn btn-outline-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                                    <path
                                                        d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                                                </svg>
                                            </div>
                                            <a class="btn btn-outline-warning" href="detail.php?id=<?php echo $row['id']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </a>
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
    
    <!-- FOOTER -->
    <?php
    include '../../views/footer.php';
    ?>

    <script src="../../js/menu.js"></script>
</body>

</html>