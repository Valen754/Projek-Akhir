<?php
include '../../koneksi.php';
include '../../views/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan data reservasi pengguna
$query = "SELECT * FROM reservasi WHERE user_id = ? ORDER BY tanggal_reservasi DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .reservasi-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .reservasi-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .reservasi-table {
            width: 100%;
            border-collapse: collapse;
        }
        .reservasi-table th, .reservasi-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .reservasi-table th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #555;
        }
        .reservasi-table tr:hover {
            background-color: #f9f9f9;
        }
        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }
        .status-dikonfirmasi {
            color: #27ae60;
            font-weight: bold;
        }
        .status-dibatalkan {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="reservasi-container">
        <h2 class="reservasi-header">Riwayat Reservasi</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="reservasi-table">
                <thead>
                    <tr>
                        <th>Kode Reservasi</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Jumlah Orang</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['kode_reservasi']); ?></td>
                            <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_reservasi']))); ?></td>
                            <td><?php echo htmlspecialchars(date('H:i', strtotime($row['tanggal_reservasi']))); ?></td>
                            <td><?php echo htmlspecialchars($row['jumlah_orang']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td>
                                <span class="<?php echo 'status-' . strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #555;">Tidak ada reservasi yang ditemukan.</p>
        <?php endif; ?>
            <div style="text-align: center; margin-top: 20px;">
            <a href="../profil/profil.php" style="text-decoration: none; color: #333; font-weight: bold; font-size: 16px;">&larr; Kembali ke Profil</a>
        </div>
    </div>
    <?php include '../../views/footer.php'; ?>
</body>
</html>