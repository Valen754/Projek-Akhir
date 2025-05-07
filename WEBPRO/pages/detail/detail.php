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
            </div>

            <!-- Detail Produk -->
            <div class="kolom-detail">
                <h2><?php echo $product['nama']; ?></h2>
                <p><?php echo $product['deskripsi']; ?></p>
                <h3 id="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include '../../views/footer.php'; ?>
</body>

</html>