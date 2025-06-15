<?php
include '../../koneksi.php';
include '../../views/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan data reservasi pengguna, termasuk email dan no_telp dari tabel users
// Updated to join with reservation_status
$query = "SELECT r.*, u.email, u.no_telp, rs.status_name AS reservation_status_name 
          FROM reservasi r 
          JOIN users u ON r.user_id = u.id 
          JOIN reservation_status rs ON r.status_id = rs.id -- Join to get status name
          WHERE r.user_id = ? ORDER BY r.tanggal_reservasi DESC";
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
            background: #8d6748;
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

        .reservasi-table th,
        .reservasi-table td {
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

        /* Tab Navigation Styles */
        .tab-navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 10px;
        }

        .tab-link {
            padding: 12px 24px;
            text-decoration: none;
            color: #666;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: transparent;
            position: relative;
            overflow: hidden;
        }

        .tab-link:hover {
            color: #8d6748;
            background: rgba(141, 103, 72, 0.1);
        }

        .tab-link.active {
            color: #fff;
            background: #8d6748;
            box-shadow: 0 2px 4px rgba(141, 103, 72, 0.3);
        }

        .tab-link.active:hover {
            background: #725539;
        }

        /* Add subtle animation */
        @keyframes tabHover {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .tab-link:hover {
            animation: tabHover 0.3s ease;
        }
    </style>
</head>

<body>
    <div class="reservasi-container">
        <h2 class="reservasi-header">Riwayat Reservasi</h2>

        <div class="tab-navigation">
            <a href="riwayat_reservasi.php" class="tab-link active">Semua Reservasi</a>
            <a href="reservasi_ditolak.php" class="tab-link">Reservasi Ditolak</a>
        </div>

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
                                <span class="<?php echo 'status-' . strtolower($row['reservation_status_name']); ?>">
                                    <?php echo htmlspecialchars($row['reservation_status_name']); ?>
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
            <a href="../profil/profil.php"
                style="text-decoration: none; color: #333; font-weight: bold; font-size: 16px;">&larr; Kembali ke
                Profil</a>
        </div>
    </div> <?php include '../../views/footer.php'; ?>
</body>

</html>