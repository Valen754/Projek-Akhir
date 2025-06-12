<?php
include '../../koneksi.php'; //
session_start();

$order_id = $_GET['id'] ?? 0;

// Ambil data pembayaran
// Query diubah: JOIN dengan users untuk customer_name; total_amount tidak ada di tabel pembayaran
$stmt = $conn->prepare("SELECT p.id, p.user_id, p.order_date, p.status, p.payment_method, p.order_type, 
                               u.nama AS customer_name 
                        FROM pembayaran p 
                        JOIN users u ON p.user_id = u.id 
                        WHERE p.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$pembayaran = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pembayaran) {
    echo "<h2>Struk tidak ditemukan.</h2>";
    exit;
}

// Ambil detail item
// Query diubah: order_id diganti pembayaran_id, subtotal dihapus dari SELECT
$stmt = $conn->prepare("SELECT d.id, d.pembayaran_id, d.menu_id, d.quantity, d.price_per_item, d.item_notes, m.nama 
                        FROM detail_pembayaran d 
                        JOIN menu m ON d.menu_id = m.id 
                        WHERE d.pembayaran_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$detail = $stmt->get_result();
$stmt->close();

// Hitung total_amount secara manual dari detail_pembayaran
$total_amount_calculated = 0;
$detail_items = []; // Simpan detail ke array terpisah untuk iterasi kedua
while ($row_detail = $detail->fetch_assoc()) {
    $item_subtotal_calculated = $row_detail['quantity'] * $row_detail['price_per_item'];
    $total_amount_calculated += $item_subtotal_calculated;
    $detail_items[] = array_merge($row_detail, ['subtotal_calculated' => $item_subtotal_calculated]);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; }
        .struk-container { background: #fff; max-width: 400px; margin: 40px auto; padding: 24px 32px; border-radius: 12px; box-shadow: 0 2px 16px #0002; }
        h2 { text-align: center; margin-bottom: 16px; }
        .info { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { padding: 6px 4px; text-align: left; }
        th { border-bottom: 1px solid #ccc; }
        tfoot td { font-weight: bold; }
        .center { text-align: center; }
        .btn-cetak { display: block; width: 100%; margin-top: 16px; padding: 10px; background: #e07b6c; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .btn-kembali{width:100%;margin-top:8px;padding:10px 0;border:none;border-radius:10px;background:#222b3a;color:#e07b6c; cursor: pointer;}
    </style>
</head>
<body>
<div class="struk-container">
    <h2>Struk Pembayaran</h2>
    <div class="info">
        <div>No. Order: <b><?= $pembayaran['id'] ?></b></div>
        <div>Tanggal: <?= date('d M Y H:i', strtotime($pembayaran['order_date'])) ?></div>
        <div>Customer: <?= htmlspecialchars($pembayaran['customer_name'] ?: '-') ?></div>
        <div>Jenis Order: <?= $pembayaran['order_type'] == 'dine_in' ? 'Dine In' : 'Take Away' ?></div>
        <div>Metode: <?= strtoupper($pembayaran['payment_method']) ?></div>
        <div>Status: <?= ucfirst($pembayaran['status']) ?></div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($detail_items as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>Rp<?= number_format($row['price_per_item'], 0, ',', '.') ?></td>
                <td>Rp<?= number_format($row['subtotal_calculated'], 0, ',', '.') ?></td>
            </tr>
            <?php if (!empty($row['item_notes'])): ?>
            <tr>
                <td colspan="4" style="font-size:12px;color:#888;">Catatan: <?= htmlspecialchars($row['item_notes']) ?></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>Rp<?= number_format($total_amount_calculated, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
    <div class="center">
        <button class="btn-cetak" onclick="window.print()">Cetak Struk</button>
        <button class="btn-kembali" onclick="window.location.href='kasir.php'">Kembali</button>
    </div>
</div>
</body>
</html>