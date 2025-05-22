<?php
include '../../koneksi.php'; // Koneksi ke database

// Query untuk mendapatkan reservasi dengan status 'pending'
$sql = "SELECT * FROM reservasi 
        JOIN users ON reservasi.user_id = users.id 
        WHERE status = 'pending' 
        ORDER BY reservasi.created_at";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Notifikasi Reservasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        .notification-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
        }

        .notification-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .notification-card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .notification-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .notification-card .actions {
            display: flex;
            gap: 10px;
        }

        .notification-card .actions button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .notification-card .actions .btn-confirm {
            background-color: #28a745;
            color: #fff;
        }

        .notification-card .actions .btn-cancel {
            background-color: #dc3545;
            color: #fff;
        }

        .no-notifications {
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
                <h1>Notifikasi Reservasi</h1>
                <p>Daftar reservasi baru yang perlu dikonfirmasi</p>
            </header>
            <section class="notification-section" aria-label="Reservation Notifications">
                <div class="notification-container">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="notification-card">
                                <div class="notification-info">
                                    <h3>Kode Reservasi: <?php echo $row['kode_reservasi']; ?></h3>
                                    <p><strong>Nama:</strong> <?php echo $row['nama']; ?></p>
                                    <p><strong>Jumlah Orang:</strong> <?php echo $row['jumlah_orang']; ?></p>
                                    <p><strong>Tanggal:</strong> <?php echo $row['tanggal_reservasi']; ?></p>
                                    <p><strong>Pesan:</strong> <?php echo $row['message']; ?></p>
                                </div>
                                <div class="actions">
                                    <form method="POST" action="update_reservasi.php">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="action" value="confirm" class="btn-confirm">Konfirmasi</button>
                                        <button type="submit" name="action" value="cancel" class="btn-cancel">Batalkan</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='no-notifications'>Tidak ada reservasi baru.</p>";
                    }
                    ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>