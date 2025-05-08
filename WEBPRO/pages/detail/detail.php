<?php
include '../../koneksi.php'; // Koneksi ke database

// Ambil ID produk dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil detail produk berdasarkan ID
    $sql = "SELECT * FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika produk ditemukan
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Jika produk tidak ditemukan, arahkan kembali ke halaman menu
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href = '../menu/menu.php';</script>";
        exit();
    }
} else {
    // Jika ID tidak valid, arahkan kembali ke halaman menu
    echo "<script>alert('ID produk tidak valid!'); window.location.href = '../menu/menu.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | <?php echo $product['nama']; ?></title>
    <link rel="stylesheet" href="../../css/detail.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!--BAGIAN NAVBAR-->
    <?php include '../../views/header.php'; ?>

    <!-- BREADCRUMB -->
    <div class="wadah-breadcrumb">
        <nav class="navigasi-breadcrumb" aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li><a href="../menu/menu.php">Menu</a></li>
                <li><?php echo ucfirst($product['type']); ?></li>
                <li class="aktif"><?php echo $product['nama']; ?></li>
            </ul>
        </nav>
    </div>

    <!-- ISI -->
    <div class="wadah-isi">
        <div class="kontainer-produk">
            <!-- Gambar -->
            <div class="kolom-gambar">
                <img src="../../asset/<?php echo $product['url_foto']; ?>" alt="<?php echo $product['nama']; ?>">
                <div class="icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        class="bi-bi-heart" viewBox="0 0 16 16">
                        <path
                            d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                    </svg>
                    <div class="favorit"> (Favorit 10K)</div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        class="bi bi-share" viewBox="0 0 16 16">
                        <path
                            d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.5 2.5 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5m-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3" />
                    </svg>
                    <img src="Foto/instagram-1-svgrepo-com.svg" style="width: 22px; height: 22px;">
                    <img src="Foto/whatsapp-whats-app-svgrepo-com.svg" style="width: 22px; height: 22px;">
                    <img src="Foto/twitter-svgrepo-com.svg" style="width: 26px; height: 26px;">
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="kolom-detail">
                <h2><?php echo $product['nama']; ?></h2>
                <div class="rating-bin">
                    <!-- Bintang Rating -->
                    <span class="bintang-bin">&#9733;</span>
                    <span class="bintang-bin">&#9733;</span>
                    <span class="bintang-bin">&#9733;</span>
                    <span class="bintang-bin">&#9733;</span>
                    <span class="bintang-bin">&#9734;</span> <!-- Bintang kosong -->
                    <span class="rating-text">4/5</span>
                </div>
                <p><?php echo $product['deskripsi']; ?></p>
                <h3 id="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h3>
                <div class="quantity-price">
                    <div class="quantity-selector">
                        <button id="decrease" class="btn-adjust">&minus;</button>
                        <span id="quantity">1</span>
                        <button id="increase" class="btn-adjust">&plus;</button>
                    </div>
                    <h3 id="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h3>
                </div>

                <div class="button-group">
                    <button class="tombol-add-to-cart" id="cartModal" style="font-family: inherit;">Add to Cart</button>
                    <button class="tombol-beli" id="openModal" style="font-family: inherit;">Buy Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- KOMENTAR -->
    <div class="wadah-komentar" id="komen">
        <h3>Comments</h3>
        <div class="form-komentar">
            <textarea id="inputKomentar" placeholder="Write your comment here..."></textarea>
            <div class="rating-input">
                <span class="bintangf" data-value="1">&#9733;</span>
                <span class="bintangf" data-value="2">&#9733;</span>
                <span class="bintangf" data-value="3">&#9733;</span>
                <span class="bintangf" data-value="4">&#9733;</span>
                <span class="bintangf" data-value="5">&#9733;</span>
                <button type="button" class="tombol-submit" id="submitKomentar">Submit</button>
            </div>
        </div>
        <div class="list-komentar">
            <!-- KOMENTAR YANG SUDAH ADA -->
            <div class="komentar-item">
                <div class="komentar-header">
                    <img src="Foto/Profil/1.jpg" alt="Foto Profil" class="foto-profil">
                    <h4>Haykal<span class="waktu-komentar">2 hours ago</span></h4>
                    <div class="rating">
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9733;</span>
                    </div>
                </div>
                <p>Mantap</p>
            </div>

            <div class="komentar-item">
                <div class="komentar-header">
                    <img src="Foto/Profil/2.jpg" alt="Foto Profil" class="foto-profil">
                    <h4>Salman <span class="waktu-komentar">1 day ago</span></h4>
                    <div class="rating">
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9733;</span>
                        <span class="bintang">&#9734;</span>
                        <span class="bintang">&#9734;</span>
                    </div>
                </div>
                <p>Kopinya pahit banget kok bisa ya ada orang yang suka kopi.</p>
            </div>
        </div>
    </div>

    <!-- REKOMENDASI -->
    <div class="wadah-rekomendasi">
        <h3>Recommendations for you</h3>
        <div class="baris-rekomendasi">
            <div class="kolom-rekomendasi">
                <a href="detail.html">
                    <img src="Foto/Kopi/Real/CAPPUCINO.jpg" alt="Rekomendasi Produk">
                </a>
            </div>
            <div class="kolom-rekomendasi">
                <a href="#">
                    <img src="Foto/Kopi/Real/ES KOPI SUSU.jpg" alt="Rekomendasi Produk">
                </a>
            </div>
            <div class="kolom-rekomendasi">
                <a href="#">
                    <img src="Foto/Kopi/Real/ESPRESSO.jpg" alt="Rekomendasi Produk">
                </a>
            </div>
            <div class="kolom-rekomendasi">
                <a href="#">
                    <img src="Foto/Kopi/Real/VIETNAM.jpg" alt="Rekomendasi Produk">
                </a>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include '../../views/footer.php'; ?>
</body>

</html>