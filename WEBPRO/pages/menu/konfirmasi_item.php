<?php
include '../../koneksi.php';
include '../../views/header.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Ambil ID menu dari query string
$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;

if ($menu_id <= 0) {
    echo "ID menu tidak valid.";
    exit();
}

// Ambil detail menu dari database
$stmt_menu = $conn->prepare("SELECT id, nama, url_foto, price, deskripsi FROM menu WHERE id = ?");
$stmt_menu->bind_param("i", $menu_id);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();
$menu_item = $result_menu->fetch_assoc();
$stmt_menu->close();

if (!$menu_item) {
    echo "Menu tidak ditemukan.";
    exit();
}

$base_gambar_url = '../../assets/menu/'; // Pastikan folder ini sesuai
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - <?php echo htmlspecialchars($menu_item['nama']); ?></title>
    <link rel="stylesheet" href="../../css/konfirmasi_item.css">
</head>

<body>
    <main class="konfirmasi-container">
        <h2>Konfirmasi Pesanan: <?php echo htmlspecialchars($menu_item['nama']); ?></h2>

        <div class="item-info">
            <img src="<?php echo $base_gambar_url . htmlspecialchars($menu_item['url_foto']); ?>"
                alt="<?php echo htmlspecialchars($menu_item['nama']); ?>">
            <p><strong>Harga Satuan:</strong> Rp <?php echo number_format($menu_item['price'], 0, ',', '.'); ?></p>
            <p><?php echo nl2br(htmlspecialchars($menu_item['deskripsi'])); ?></p>
        </div>

        <form action="logic/proses_konfirmasi_item.php" method="POST">
            <input type="hidden" name="menu_id" value="<?php echo $menu_item['id']; ?>">
            <input type="hidden" name="harga_satuan" value="<?php echo $menu_item['price']; ?>">

            <div class="form-group">
                <label for="jumlah">Jumlah:</label>
                <input type="number" id="jumlah" name="jumlah" value="1" min="1" required>
            </div>

            <div class="form-group">
                <label for="memo">Memo Pesanan (opsional):</label>
                <textarea id="memo" name="memo" placeholder="Contoh: Tidak pedas, ekstra saus, dll."></textarea>
            </div>

            <div class="action-buttons">
                <button type="submit" name="action" value="tambah_keranjang" class="btn-add-to-cart">Masukkan ke
                    Keranjang</button>
                <button type="submit" name="action" value="langsung_bayar" class="btn-direct-pay">Langsung
                    Bayar</button>
            </div>
        </form>
    </main>

    <?php include '../../views/footer.php'; ?>
</body>

</html>