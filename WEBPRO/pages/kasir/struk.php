<?php
include '../../koneksi.php';
$order_id = $_GET['id'] ?? 0;

// Ambil data order & details
$q = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
$order = mysqli_fetch_assoc($q);

$q2 = mysqli_query($conn, "SELECT od.*, m.nama, m.url_foto FROM order_details od JOIN menu m ON od.menu_id = m.id WHERE od.order_id = '$order_id'");
$details = [];
while ($row = mysqli_fetch_assoc($q2)) $details[] = $row;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <link rel="stylesheet" href="../../css/kasir.css">
    <style>
        .struk-container { max-width: 400px; margin: 32px auto; background: #fff; color: #222; border-radius: 12px; padding: 32px; }
        .struk-header { text-align: center; margin-bottom: 24px;}
        .struk-header h2 { margin: 0; font-size: 20px;}
        .struk-body { margin-bottom: 24px; }
        .struk-item { display: flex; justify-content: space-between; border-bottom: 1px dashed #ccc; padding: 4px 0;}
        .struk-total { font-weight: bold; font-size: 16px; }
        @media print { body { background: #fff; } .struk-container { box-shadow:none; } button { display:none; } }
    </style>
</head>
<body>
    <div class="struk-container">
        <div class="struk-header">
            <h2>TAPAL KUDA CAFE</h2>
            <div><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></div>
            <div>Kasir: <?= htmlspecialchars($order['customer_name'] ?: '-') ?></div>
            <div>Metode: <?= htmlspecialchars($order['payment_method']) ?></div>
        </div>
        <div class="struk-body">
            <?php foreach($details as $item): ?>
            <div class="struk-item">
                <div>
                    <?= htmlspecialchars($item['nama']) ?> x<?= $item['quantity'] ?>
                    <?php if ($item['item_notes']) echo "<div><small>Catatan: ".htmlspecialchars($item['item_notes'])."</small></div>"; ?>
                </div>
                <div>Rp<?= number_format($item['subtotal'],0,',','.') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="struk-total">
            Total: Rp<?= number_format($order['total_amount'],0,',','.') ?>
        </div>
        <button onclick="window.print()" style="margin-top:24px;width:100%;padding:10px 0;border-radius:8px;background:#e07b6c;color:#fff;border:none;">Cetak Struk</button>
        <a href="kasir.php" style="display:block;text-align:center;margin-top:10px;color:#e07b6c;">Kembali ke kasir</a>
    </div>
</body>
</html>
