<?php
include '../../koneksi.php'; // Sesuaikan path koneksi Anda
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran 'kasir'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../../login/login.php");
    exit();
}

// Ambil semua riwayat pesanan dari tabel checkout
// Gabungkan dengan tabel menu untuk mendapatkan nama menu
// Gabungkan dengan tabel users untuk mendapatkan nama user yang melakukan checkout
$sql = "SELECT 
            c.checkout_id, 
            c.total_price, 
            c.created_at, 
            u.username, 
            m.nama AS menu_nama,
            c.menu_id -- Tambahkan menu_id untuk identifikasi lebih lanjut jika diperlukan
        FROM 
            checkout c
        JOIN 
            users u ON c.user_id = u.id
        JOIN
            menu m ON c.menu_id = m.id
        ORDER BY 
            c.created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        .riwayat-container {
            padding: 20px;
        }

        .riwayat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .riwayat-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .riwayat-card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .riwayat-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .no-riwayat {
            text-align: center;
            font-size: 16px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container" role="main">
        <?php include '../../views/kasir/sidebar.php'; ?>
        
        <main>
            <header>
                <h1>Riwayat Pesanan</h1>
                <p>Daftar semua pesanan yang telah diselesaikan oleh kasir.</p>
            </header>
            <section class="riwayat-section" aria-label="Order History">
                <div class="riwayat-container">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="riwayat-grid">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="riwayat-card">
                                    <h3>Pesanan #<?php echo $row['checkout_id']; ?></h3>
                                    <p><strong>Pembeli:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
                                    <p><strong>Menu:</strong> <?php echo htmlspecialchars($row['menu_nama']); ?></p>
                                    <p><strong>Total Harga:</strong> Rp <?php echo number_format($row['total_price'], 0, ',', '.'); ?></p>
                                    <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i:s', strtotime($row['created_at'])); ?></p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-riwayat">Belum ada riwayat pesanan.</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>