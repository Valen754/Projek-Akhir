<?php
include '../../koneksi.php'; // Sesuaikan path koneksi Anda
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

// Ambil riwayat pesanan dari tabel 'orders'
// Kita akan melakukan JOIN ke 'order_details' dan 'menu' nanti di dalam loop
$sql_orders = "SELECT
                p.id AS order_id,
                p.order_date,
                p.total_amount,
                p.status,
                u.username AS kasir_username,
                p.customer_name,
                p.payment_method,
                p.order_type,
                p.notes
            FROM
                pembayaran p
            JOIN
                users u ON p.user_id = u.id
            ORDER BY
                p.order_date DESC";
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
    <!-- Tambahkan ini di <head> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        main {
            margin: 20px auto;
            width: 90%;
            padding-left: 80px;
            /* Sesuaikan dengan lebar sidebar */
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
            color: white;
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
            color: #333;
            /* Warna teks untuk detail item */
        }

        .order-details-content li:last-child {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="container" style="display: flex; min-height: 100vh;">
        <?php $activePage = 'history'; ?>
        <?php include '../../views/kasir/sidebar.php'; ?>

        <main style="flex:1; margin: 32px 0 32px 90px;">
            <header style="margin-bottom: 24px;">
                <h1 style="margin-bottom: 8px;">Riwayat Pesanan</h1>
                <p style="color: #666;">Daftar semua pesanan yang telah diselesaikan oleh kasir.</p>
            </header>

            <div class="data-table-container">
                <div style="font-weight: bold; margin-bottom: 12px;">
                    <i class="fas fa-table me-1"></i> Data Riwayat Pesanan
                </div>
                <div style="overflow-x:auto;">
                    <table id="datatablesSimple" class="table table-bordered" style="background: #fff;">
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
                                        <td><?= $no++; ?></td>
                                        <td><?= $order['order_id']; ?></td>
                                        <td><?= htmlspecialchars($order['customer_name'] ?? $order['kasir_username']); ?></td>
                                        <td><?= date('d M Y H:i:s', strtotime($order['order_date'])); ?></td>
                                        <td>Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                        <td><?= htmlspecialchars($order['payment_method']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php
                                            if ($order['status'] == 'completed')
                                                echo 'success';
                                            else if ($order['status'] == 'pending')
                                                echo 'warning';
                                            else
                                                echo 'danger';
                                            ?>">
                                                <?= ucfirst(htmlspecialchars($order['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="order-detail-toggle btn btn-sm btn-info" data-order-id="<?= $order['order_id']; ?> style='list-style' ">
                                                Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="order-details-row" id="details-<?= $order['order_id']; ?>"
                                        style="display: none;">
                                        <td colspan="8" style="background-color: #f6f8fa;">
                                            <div class="order-details-content">
                                                <h5 style="margin-bottom: 8px;">Detail Item Pesanan:</h5>
                                                <ul>
                                                    <?php
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
                                                            <li style="margin-bottom: 6px;">
                                                                <img src="../../asset/<?= htmlspecialchars($item_detail['url_foto']); ?>"
                                                                    alt="<?= htmlspecialchars($item_detail['menu_nama']); ?>" width="30"
                                                                    height="30"
                                                                    style="vertical-align: middle; border-radius: 3px; margin-right: 5px;">
                                                                <?= htmlspecialchars($item_detail['menu_nama']); ?>
                                                                (<?= $item_detail['quantity']; ?>x)
                                                                @ Rp<?= number_format($item_detail['price_per_item'], 0, ',', '.'); ?>
                                                                = Rp<?= number_format($item_detail['subtotal'], 0, ',', '.'); ?>
                                                                <?php if (!empty($item_detail['item_notes'])): ?>
                                                                    <br><small>Catatan Item:
                                                                        <?= htmlspecialchars($item_detail['item_notes']); ?></small>
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
                                                    <p style="margin-top: 10px;"><strong>Catatan Pesanan Umum:</strong>
                                                        <?= htmlspecialchars($order['notes']); ?></p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="../../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const datatablesSimple = document.getElementById('datatablesSimple');
            let dataTable;
            if (datatablesSimple) {
                dataTable = new simpleDatatables.DataTable(datatablesSimple);
            }

            function attachToggleListeners() {
                document.querySelectorAll('.order-detail-toggle').forEach(button => {
                    button.removeEventListener('click', toggleDetailRow);
                    button.addEventListener('click', toggleDetailRow);
                });
            }

            function toggleDetailRow() {
                const orderId = this.dataset.orderId;
                const detailRow = document.getElementById(`details-${orderId}`);
                if (detailRow.style.display === 'none' || detailRow.style.display === '') {
                    detailRow.style.display = 'table-row';
                    this.textContent = 'Sembunyikan Detail';
                    this.classList.remove('btn-info');
                    this.classList.add('btn-secondary');
                } else {
                    detailRow.style.display = 'none';
                    this.textContent = 'Lihat Detail';
                    this.classList.remove('btn-secondary');
                    this.classList.add('btn-info');
                }
            }

            attachToggleListeners();

            if (typeof dataTable !== 'undefined') {
                dataTable.on('datatable.page', attachToggleListeners);
                dataTable.on('datatable.sort', attachToggleListeners);
                dataTable.on('datatable.search', attachToggleListeners);
            }
        });
    </script>
</body>

</html>