<?php
include '../../views/header.php';
include '../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
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
            background: #8d6748;
            margin: 0;
            padding: 0;
        }
        .riwayat-header {
            background: #a67c52;
            color: #fff;
            text-align: center;
            padding: 32px 0 18px 0;
            font-size: 2em;
            letter-spacing: 1px;
        }
        .riwayat-list {
            max-width: 600px;
            margin: 30px auto;
            background: #e0d3c2;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .riwayat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid #c2b09a;
            font-size: 1.1em;
        }
        .riwayat-item:last-child {
            border-bottom: none;
        }
        .riwayat-info {
            display: flex;
            flex-direction: column;
        }
        .riwayat-date {
            font-size: 0.95em;
            color: #7a5a3a;
        }
        .riwayat-total {
            font-weight: bold;
            color: #6d4c2b;
        }
        .riwayat-status {
            font-size: 0.95em;
            color: #fff;
            background: #a67c52;
            padding: 3px 12px;
            border-radius: 8px;
            margin-left: 8px;
        }
        @media (max-width: 700px) {
            .riwayat-list { max-width: 98%; }
            .riwayat-item { padding: 14px 10px; }
        }
    </style>
</head>
<body>
    <div class="riwayat-header">
        <i class='bx bx-receipt'></i> Riwayat Pesanan
    </div>
    <div class="riwayat-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="riwayat-item">
                    <div class="riwayat-info">
                        <span>#<?php echo $row['id']; ?> - <?php echo date('d M Y', strtotime($row['order_date'])); ?></span>
                        <span class="riwayat-date"><?php echo htmlspecialchars($row['alamat'] ?? ''); ?></span>
                    </div>
                    <div>
                        <span class="riwayat-total">Rp <?php echo number_format($row['total_amount'],0,',','.'); ?></span>
                        <?php if (isset($row['status'])): ?>
                            <span class="riwayat-status"><?php echo htmlspecialchars($row['status']); ?></span>
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