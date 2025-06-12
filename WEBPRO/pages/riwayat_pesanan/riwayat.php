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

// Perubahan: Membangun query utama dengan prepared statement untuk keamanan dan perhitungan total_amount
$sql_main = "SELECT p.id, p.order_date, p.status, p.payment_method, p.order_type,
                    u.nama AS customer_name,
                    SUM(od.quantity * od.price_per_item) AS total_amount
             FROM pembayaran p
             JOIN users u ON p.user_id = u.id
             LEFT JOIN detail_pembayaran od ON p.id = od.pembayaran_id
            ";

$where_clauses = ["p.user_id = ?"];
$params = [$user_id];
$types = "i";

if ($tanggal_awal && $tanggal_akhir) {
    $where_clauses[] = "DATE(p.order_date) BETWEEN ? AND ?";
    $params[] = $tanggal_awal;
    $params[] = $tanggal_akhir;
    $types .= "ss";
} elseif ($tanggal_awal) {
    $where_clauses[] = "DATE(p.order_date) >= ?";
    $params[] = $tanggal_awal;
    $types .= "s";
} elseif ($tanggal_akhir) {
    $where_clauses[] = "DATE(p.order_date) <= ?";
    $params[] = $tanggal_akhir;
    $types .= "s";
}

$sql_main .= " WHERE " . implode(" AND ", $where_clauses);
$sql_main .= " GROUP BY p.id, p.order_date, p.status, u.nama, p.payment_method, p.order_type ORDER BY p.order_date DESC";

$stmt_main = $conn->prepare($sql_main);
if ($types) {
    $stmt_main->bind_param($types, ...$params);
}
$stmt_main->execute();
$result = $stmt_main->get_result();
$stmt_main->close();

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
                <?php
                $order_id = $order['id'];
                // Perubahan: od.order_id menjadi od.pembayaran_id, dan hilangkan od.subtotal dari SELECT
                $q_detail = "SELECT od.quantity, od.price_per_item, od.item_notes, m.nama, m.url_foto 
                             FROM detail_pembayaran od
                             JOIN menu m ON od.menu_id = m.id
                             WHERE od.pembayaran_id = ?";
                $stmt_detail = $conn->prepare($q_detail); // Gunakan prepared statement
                $stmt_detail->bind_param("i", $order_id);
                $stmt_detail->execute();
                $res_detail = $stmt_detail->get_result();
                $stmt_detail->close();
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
                                        <?php if (!empty($item['item_notes'])): ?>
                                            <br><small>Catatan:
                                                <?= htmlspecialchars($item['item_notes']); ?></small>
                                        <?php endif; ?>
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