<?php
include '../../koneksi.php'; // Koneksi ke database
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Pastikan hanya pengguna dengan role 'kasir' yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'kasir') {
    header("Location: ../login/login.php"); // Arahkan ke halaman login jika role tidak sesuai
    exit();
}

// Query untuk mendapatkan reservasi dengan status 'pending' - Updated to join with reservation_status
$sql = "SELECT r.*, u.nama 
        FROM reservasi r 
        JOIN users u ON r.user_id = u.id 
        JOIN reservation_status rs ON r.status_id = rs.id -- Join to get status name
        WHERE rs.status_name = 'pending' 
        ORDER BY r.created_at";
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
        <?php $activePage = 'notifikasi'; ?>
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
                                    <h3>Kode Reservasi: <?php echo htmlspecialchars($row['kode_reservasi']); ?></h3>
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($row['nama']); ?></p>
                                    <p><strong>Jumlah Orang:</strong> <?php echo htmlspecialchars($row['jumlah_orang']); ?></p>
                                    <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($row['tanggal_reservasi']); ?></p>
                                    <p><strong>Pesan:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                                </div>
                                <div class="actions">
                                    <form action="logic/update_reservasi.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <input type="hidden" name="action" value="confirm">
                                        <button type="submit" class="btn-confirm">Konfirmasi</button>
                                    </form>
                                    <button
                                        onclick="document.getElementById('cancelModal').style.display='block'; document.getElementById('reservationId').value='<?php echo htmlspecialchars($row['id']); ?>'"
                                        class="btn-cancel">Batalkan</button>
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
    <div id="cartInputModal"
        style="display:none;position:fixed;z-index:10001;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:8px;max-width:350px;margin:120px auto 0;box-shadow:0 2px 8px rgba(0,0,0,0.15);position:relative;">
            <span id="closeCartInputModal"
                style="position:absolute;top:8px;right:16px;cursor:pointer;font-size:22px;">&times;</span>
            <h3>Tambah ke Keranjang</h3>
            <form id="cartInputForm">
                <input type="hidden" id="cartInputId">
                <input type="hidden" id="cartInputNama">
                <input type="hidden" id="cartInputHarga">
                <input type="hidden" id="cartInputFoto">
                <input type="hidden" id="cartInputStok">
                <div style="margin-bottom:10px;">
                    <label>Jumlah:</label>
                    <input type="number" id="cartInputQty" min="1" value="1" style="width:60px;">
                    <span id="cartInputStokInfo" style="font-size:12px;color:#888;"></span>
                </div>
                <div style="margin-bottom:10px;">
                    <label>Catatan:</label>
                    <textarea id="cartInputNote" rows="2" style="width:100%; border-radius: 5px;"></textarea>
                </div>
                <button type="submit"
                    style="background:#6d4c2b;color:#fff;padding:8px 18px;border:none;border-radius:4px;cursor:pointer;">Tambah</button>
            </form>
        </div>
    </div> 
    <div id="cancelModal"
        style="display:none;position:fixed;z-index:10001;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:8px;max-width:450px;margin:120px auto 0;box-shadow:0 2px 8px rgba(0,0,0,0.15);position:relative;">
            <span onclick="document.getElementById('cancelModal').style.display='none'"
                style="position:absolute;top:8px;right:16px;cursor:pointer;font-size:22px;">&times;</span>
            <h3>Alasan Pembatalan Reservasi</h3>
            <form action="logic/update_reservasi.php" method="POST">
                <input type="hidden" id="reservationId" name="id">
                <input type="hidden" name="action" value="cancel">
                <div style="margin-bottom:15px;">
                    <label style="display:block;margin-bottom:5px;">Alasan Pembatalan:</label>
                    <textarea name="reason" rows="4"
                        style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" 
                        placeholder="Masukkan alasan pembatalan reservasi..." required></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" 
                        onclick="document.getElementById('cancelModal').style.display='none'"
                        style="padding:8px 16px;border:1px solid #ddd;border-radius:4px;background:#fff;">
                        Batal
                    </button>
                    <button type="submit"
                        style="padding:8px 16px;border:none;border-radius:4px;background:#dc3545;color:#fff;">
                        Konfirmasi Pembatalan
                    </button>
                </div>
            </form>
        </div>
    </div><?php
    // Tampilkan pesan status jika ada
    if (isset($_GET['status']) && isset($_GET['message'])) {
        $alertClass = $_GET['status'] === 'success' ? 'success' : 'error';
        echo "<div class='alert alert-{$alertClass}' style='padding: 15px; margin: 10px; border-radius: 4px; background-color: " .
            ($_GET['status'] === 'success' ? '#d4edda' : '#f8d7da') . "; color: " .
            ($_GET['status'] === 'success' ? '#155724' : '#721c24') . ";'>" .
            htmlspecialchars($_GET['message']) . "</div>";
    }
    ?>     <script>
        // Tutup modal ketika mengklik di luar modal
        window.onclick = function (event) {
            if (event.target.id === 'cancelModal') {
                document.getElementById('cancelModal').style.display = 'none';
            }
        }
    </script>
</body>

</html>