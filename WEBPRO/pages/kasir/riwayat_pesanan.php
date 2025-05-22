<?php
include '../../koneksi.php'; // Sesuaikan path koneksi Anda
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran 'kasir'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../../login/login.php");
    exit();
}

// Ambil riwayat pesanan dari tabel 'orders'
$sql_orders = "SELECT
                o.id AS order_id,
                o.order_date,
                o.total_amount,
                o.status,
                u.username AS kasir_username,
                o.customer_name,
                o.payment_method,
                o.notes
            FROM
                orders o
            JOIN
                users u ON o.user_id = u.id
            ORDER BY
                o.order_date DESC";
$result_orders = $conn->query($sql_orders);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Riwayat Pesanan Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet"> <style>
        main {
            margin: 20px auto;
            width: 90%;
            padding-left: 80px; /* Sesuaikan dengan lebar sidebar */
            /* Anda mungkin tidak perlu padding-right jika tidak ada order panel di halaman ini */
        }
        table {
            margin: 0 auto;
            width: 100%;
        }
        .data-table-container {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .order-detail-toggle {
            cursor: pointer;
            color: #007bff;
        }
        .order-detail-toggle:hover {
            text-decoration: underline;
        }
        /* Baris detail pesanan yang akan disembunyikan/ditampilkan */
        .order-details-row {
            display: none; /* Sembunyikan secara default */
            background-color: #e9ecef;
        }
        .order-details-content {
            padding: 10px 20px;
        }
        .order-details-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .order-details-content li {
            margin-bottom: 5px;
        }
        .order-details-content li:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <div class="container" role="main">
        <?php include '../../views/kasir/sidebar.php'; /*cite: valen754/projek-akhir/Projek-Akhir-c080af7e4fecb96a5f49502d626f1fcf9c276a3c/WEBPRO/views/kasir/sidebar.php*/ ?>
        
        <main>
            <header>
                <h1>Riwayat Pesanan</h1>
                <p>Daftar semua pesanan yang telah diselesaikan oleh kasir.</p>
            </header>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Data Riwayat Pesanan
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pesanan</th>
                                <th>Pembeli</th>
                                <th>Tanggal Pesanan</th>
                                <th>Total Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_orders->num_rows > 0) {
                                $no = 1;
                                while ($order = $result_orders->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?? $order['kasir_username']); ?></td>
                                    <td><?php echo date('d M Y H:i:s', strtotime($order['order_date'])); ?></td>
                                    <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                            if ($order['status'] == 'completed') echo 'success';
                                            else if ($order['status'] == 'pending') echo 'warning';
                                            else echo 'danger';
                                        ?>">
                                            <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="order-detail-toggle btn btn-sm btn-info" data-order-id="<?php echo $order['order_id']; ?>">Lihat Detail</button>
                                    </td>
                                </tr>
                                {{-- Baris tersembunyi untuk detail pesanan --}}
                                <tr class="order-details-row" id="details-<?php echo $order['order_id']; ?>">
                                    <td colspan="8">
                                        <div class="order-details-content">
                                            <h5>Detail Item Pesanan:</h5>
                                            <ul>
                                                <?php
                                                // Ambil detail item untuk pesanan ini
                                                $sql_order_details = "SELECT
                                                                        od.quantity,
                                                                        od.price_per_item,
                                                                        od.subtotal,
                                                                        od.item_notes,
                                                                        m.nama AS menu_nama,
                                                                        m.url_foto
                                                                    FROM
                                                                        order_details od
                                                                    JOIN
                                                                        menu m ON od.menu_id = m.id
                                                                    WHERE
                                                                        od.order_id = ?";
                                                $stmt_order_details = $conn->prepare($sql_order_details);
                                                $stmt_order_details->bind_param("i", $order['order_id']);
                                                $stmt_order_details->execute();
                                                $result_order_details = $stmt_order_details->get_result();

                                                if ($result_order_details->num_rows > 0) {
                                                    while ($item_detail = $result_order_details->fetch_assoc()) {
                                                ?>
                                                    <li>
                                                        <img src="../../asset/<?php echo htmlspecialchars($item_detail['url_foto']); ?>" alt="<?php echo htmlspecialchars($item_detail['menu_nama']); ?>" width="30" height="30" style="vertical-align: middle; border-radius: 3px; margin-right: 5px;">
                                                        <?php echo htmlspecialchars($item_detail['menu_nama']); ?> (<?php echo $item_detail['quantity']; ?>x)
                                                        @ Rp <?php echo number_format($item_detail['price_per_item'], 0, ',', '.'); ?>
                                                        = Rp <?php echo number_format($item_detail['subtotal'], 0, ',', '.'); ?>
                                                        <?php if (!empty($item_detail['item_notes'])): ?>
                                                            <br><small>Catatan Item: <?php echo htmlspecialchars($item_detail['item_notes']); ?></small>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<li>Tidak ada detail item.</li>";
                                                }
                                                $stmt_order_details->close();
                                                ?>
                                            </ul>
                                            <?php if (!empty($order['notes'])): ?>
                                                <p><strong>Catatan Pesanan Umum:</strong> <?php echo htmlspecialchars($order['notes']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada riwayat pesanan.</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../js/scripts.js"></script> <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../../js/datatables-simple-demo.js"></script> <script>
        // Memastikan DataTables diinisialisasi setelah DOM dimuat
        window.addEventListener('DOMContentLoaded', event => {
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                // Inisialisasi DataTables tanpa kolom 'Detail' untuk sorting bawaan
                // Kita akan menangani tombol 'Detail' secara manual
                const dataTable = new simpleDatatables.DataTable(datatablesSimple);

                // Nonaktifkan sorting untuk kolom 'Detail' jika Simple-DataTables mendukungnya
                // Beberapa versi atau konfigurasi mungkin memerlukan penyesuaian khusus
                // Contoh: dataTable.columns().disableSorting(columnIndex);
            }

            // Script untuk menampilkan/menyembunyikan detail pesanan
            document.querySelectorAll('.order-detail-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const detailRow = document.getElementById(`details-${orderId}`);
                    if (detailRow.style.display === 'none' || detailRow.style.display === '') {
                        detailRow.style.display = 'table-row'; // Menampilkan baris
                        this.textContent = 'Sembunyikan Detail';
                        this.classList.remove('btn-info');
                        this.classList.add('btn-secondary');
                    } else {
                        detailRow.style.display = 'none'; // Menyembunyikan baris
                        this.textContent = 'Lihat Detail';
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-info');
                    }
                });
            });
        });
    </script>
</body>
</html>