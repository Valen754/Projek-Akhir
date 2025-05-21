<?php
include '../../koneksi.php'; // Koneksi ke database

// Query untuk mendapatkan reservasi yang sudah dikonfirmasi beserta nama user
$sql = "SELECT reservasi.*, users.nama 
        FROM reservasi 
        JOIN users ON reservasi.user_id = users.id 
        WHERE reservasi.status = 'dikonfirmasi' 
        ORDER BY reservasi.created_at DESC";
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
                                    <h3>Kode Reservasi: <?php echo $row['kode_reservasi']; ?></h3>
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($row['nama']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                    <p><strong>No. Telepon:</strong> <?php echo htmlspecialchars($row['no_telp']); ?></p>
                                    <p><strong>Jumlah Orang:</strong> <?php echo $row['jumlah_orang']; ?></p>
                                    <p><strong>Tanggal:</strong> <?php echo $row['tanggal_reservasi']; ?></p>
                                    <p><strong>Pesan:</strong> <?php echo $row['message']; ?></p>
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