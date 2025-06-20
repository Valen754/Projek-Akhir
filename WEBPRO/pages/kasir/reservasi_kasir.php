<?php
include '../../koneksi.php'; // Koneksi ke database
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");// Arahkan ke halaman login jika belum login
    exit();
}

// Pastikan hanya pengguna dengan role 'kasir' yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'kasir') {
    header("Location: ../login/login.php"); // Arahkan ke halaman login jika role tidak sesuai
    exit();
}

// Query untuk mendapatkan reservasi yang sudah dikonfirmasi beserta nama, email, dan no_telp user
// Updated to join with reservation_status
$sql = "SELECT r.*, u.nama, u.email, u.no_telp, rs.status_name AS reservation_status_name 
        FROM reservasi r 
        JOIN users u ON r.user_id = u.id 
        JOIN reservation_status rs ON r.status_id = rs.id -- Join to get status name
        WHERE rs.status_name = 'dikonfirmasi' 
        ORDER BY r.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Reservasi Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        .reservasi-container {
            padding: 20px;
        }

        .reservasi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .reservasi-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .reservasi-card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .reservasi-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .reservasi-card .status {
            font-weight: bold;
            margin-top: 10px;
        }

        .status-dikonfirmasi {
            color: #28a745;
        }

        .no-reservasi {
            text-align: center;
            font-size: 16px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container" role="main">
        <?php $activePage = 'reservasi'; ?>
        <?php include '../../views/kasir/sidebar.php'; ?>
        
        <main>
            <header>
                <h1>Reservasi Kasir</h1>
                <p>Daftar semua reservasi yang telah dikonfirmasi</p>
            </header>
            <section class="reservasi-section" aria-label="Reservation List">
                <div class="reservasi-container">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="reservasi-grid">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="reservasi-card">
                                    <h3>Kode Reservasi: <?php echo htmlspecialchars($row['kode_reservasi']); ?></h3>
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($row['nama']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                    <p><strong>No. Telepon:</strong> <?php echo htmlspecialchars($row['no_telp']); ?></p>
                                    <p><strong>Jumlah Orang:</strong> <?php echo htmlspecialchars($row['jumlah_orang']); ?></p>
                                    <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($row['tanggal_reservasi']); ?></p>
                                    <p><strong>Pesan:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                                    <p class="status status-dikonfirmasi">Status: Dikonfirmasi</p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-reservasi">Tidak ada reservasi yang tersedia.</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>