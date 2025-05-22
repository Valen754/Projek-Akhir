<?php
include '../../koneksi.php';

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
            $sql = "SELECT r.comment, r.rating, r.created_at, u.username
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.menu_id = ?
                ORDER BY r.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()):
                ?>
                <div class="komentar-item">
                    <div class="komentar-header">
                        <img src="../../asset/default-avatar.jpg" alt="Foto Profil" class="foto-profil">
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
            // Logika baru ditambahkan di sini tanpa mengubah struktur HTML yang sudah ada.
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
</body>

</html>