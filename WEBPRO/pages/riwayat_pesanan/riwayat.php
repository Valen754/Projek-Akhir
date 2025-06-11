<?php
include '../../views/header.php';
include '../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Filter tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

$where = "user_id = $user_id";
if ($tanggal_awal && $tanggal_akhir) {
    $where .= " AND DATE(order_date) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
} elseif ($tanggal_awal) {
    $where .= " AND DATE(order_date) >= '$tanggal_awal'";
} elseif ($tanggal_akhir) {
    $where .= " AND DATE(order_date) <= '$tanggal_akhir'";
}

$query = "SELECT * FROM pembayaran WHERE $where ORDER BY order_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link href="../../css/riwayat.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="riwayat-header">
        <i class='bx bx-receipt'></i> Riwayat Pesanan
    </div>
    <a href="../profil/profil.php" class="back-to-profile">
        <i class='bx bx-user'></i>
        Kembali ke Profile
    </a>
    <div class="filter-container">
        <form method="get" class="filter-form">
            <label>
                Dari:
                <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>">
            </label>
            <label>
                Sampai:
                <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>">
            </label>
            <button type="submit" class="filter-btn">Filter</button>
            <?php if ($tanggal_awal || $tanggal_akhir): ?>
                <a href="riwayat.php" class="reset-link">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="riwayat-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <!-- Ambil detail menu untuk order ini -->
                <?php
                $order_id = $order['id'];
                $q_detail = "SELECT od.*, m.nama, m.url_foto 
                             FROM detail_pembayaran od
                             JOIN menu m ON od.menu_id = m.id
                             WHERE od.order_id = $order_id";
                $res_detail = $conn->query($q_detail);
                ?>
                <div class="riwayat-item" style="flex-direction:column;align-items:stretch;">
                    <div style="display:flex;align-items:center;">
                        <div class="riwayat-info" style="flex:1;">
                            <span class="riwayat-title">#<?= $order['id']; ?> - <?= date('d M Y H:i', strtotime($order['order_date'])); ?></span>
                        </div>
                        <div style="text-align:right;">
                            <span class="riwayat-total">Rp <?= number_format($order['total_amount'],0,',','.'); ?></span><br>
                            <span class="riwayat-status"><?= htmlspecialchars($order['status']); ?></span>
                        </div>
                    </div>
                    <div style="display:flex;gap:18px;flex-wrap:wrap;margin-top:12px;">
                        <?php if ($res_detail && $res_detail->num_rows > 0): ?>
                            <?php while ($item = $res_detail->fetch_assoc()): ?>
                                <div style="display:flex;align-items:center;gap:10px;background:#f8f4ef;border-radius:10px;padding:8px 14px;margin-bottom:6px;">
                                    <img src="../../asset/<?= htmlspecialchars($item['url_foto'] ?? 'default-product.png'); ?>" class="riwayat-img" style="width:54px;height:54px;">
                                    <div>
                                        <div style="font-weight:600;color:#6d4c2b;"><?= htmlspecialchars($item['nama']); ?></div>
                                        <div style="font-size:0.97em;color:#a67c52;">x<?= $item['quantity']; ?> @ Rp <?= number_format($item['price_per_item'],0,',','.'); ?></div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="riwayat-item" style="justify-content:center;">Belum ada pesanan.</div>
        <?php endif; ?>
    </div>
    <?php include '../../views/footer.php'; ?>
</body>
</html>