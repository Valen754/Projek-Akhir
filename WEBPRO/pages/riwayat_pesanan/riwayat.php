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
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background: linear-gradient(135deg, #a67c52 0%, #e0d3c2 100%);
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .riwayat-header {
            background: #6d4c2b;
            color: #fff;
            text-align: center;
            padding: 36px 0 22px 0;
            font-size: 2.3em;
            letter-spacing: 2px;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .riwayat-list {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            padding: 0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            overflow: hidden;
        }
        .riwayat-item {
            display: flex;
            align-items: center;
            padding: 22px 28px;
            border-bottom: 1px solid #e0d3c2;
            transition: background 0.2s;
        }
        .riwayat-item:hover {
            background: #f8f4ef;
        }
        .riwayat-item:last-child {
            border-bottom: none;
        }
        .riwayat-img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 24px;
            border: 2px solid #a67c52;
            background: #f3e9dd;
        }
        .riwayat-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .riwayat-title {
            font-weight: 600;
            font-size: 1.15em;
            color: #6d4c2b;
        }
        .riwayat-date {
            font-size: 0.97em;
            color: #a67c52;
            margin-top: 2px;
        }
        .riwayat-alamat {
            font-size: 0.95em;
            color: #7a5a3a;
            margin-top: 2px;
        }
        .riwayat-total {
            font-weight: bold;
            color: #a67c52;
            font-size: 1.1em;
        }
        .riwayat-status {
            font-size: 0.97em;
            color: #fff;
            background: #a67c52;
            padding: 4px 16px;
            border-radius: 10px;
            margin-left: 10px;
            display: inline-block;
        }
        @media (max-width: 700px) {
            .riwayat-list { max-width: 98%; }
            .riwayat-item { flex-direction: column; align-items: flex-start; padding: 16px 8px; }
            .riwayat-img { margin-bottom: 10px; margin-right: 0; }
        }
    </style>
</head>
<body>
    <div class="riwayat-header">
        <i class='bx bx-receipt'></i> Riwayat Pesanan
    </div>
    <div style="max-width:700px;margin:24px auto 0 auto;padding:0 10px;">
        <form method="get" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <label>Dari: <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>"></label>
            <label>Sampai: <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>"></label>
            <button type="submit" style="background:#a67c52;color:#fff;border:none;padding:6px 18px;border-radius:8px;">Filter</button>
            <?php if ($tanggal_awal || $tanggal_akhir): ?>
                <a href="riwayat.php" style="margin-left:8px;color:#a67c52;text-decoration:underline;">Reset</a>
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