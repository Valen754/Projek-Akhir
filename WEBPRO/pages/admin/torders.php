<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Ambil filter waktu dari GET
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';

$where = '';
if ($filter == 'hari') {
    $where = "WHERE DATE(o.order_date) = CURDATE()";
} elseif ($filter == 'bulan') {
    $where = "WHERE MONTH(o.order_date) = MONTH(CURDATE()) AND YEAR(o.order_date) = YEAR(CURDATE())";
} elseif ($filter == 'tahun') {
    $where = "WHERE YEAR(o.order_date) = YEAR(CURDATE())";
} elseif ($filter == 'tanggal' && !empty($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
    $where = "WHERE DATE(o.order_date) = '$tanggal'";
}

// Query SQL untuk mengambil data orders
$sql_orders = "SELECT
                o.id AS order_id,
                o.user_id,
                u.username AS kasir_username,
                o.order_date,
                o.total_amount,
                o.status,
                o.customer_name,
                o.payment_method,
                o.notes
            FROM
                pembayaran o
            JOIN
                users u ON o.user_id = u.id
            $where
            ORDER BY
                o.order_date DESC";

$result_orders = mysqli_query($conn, $sql_orders);
?>

<style>
    /* CSS tambahan untuk tampilan detail pesanan */
    .order-details-row {
        display: none; /* Sembunyikan baris detail secara default */
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
        color: #333;
    }
    .order-detail-toggle {
        cursor: pointer;
        color: #007bff;
    }
    .order-detail-toggle:hover {
        text-decoration: underline;
    }
</style>

<h1 class="mt-4">Table order</h1>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Data Orders
                        </div>
                        <form method="get" class="d-flex align-items-center" style="gap:10px;">
                            <label for="filter_waktu" class="mb-0">Filter Waktu:</label>
                            <select name="filter" id="filter_waktu" class="form-select" style="width:auto;">
                                <option value="hari" <?= $filter == 'hari' ? 'selected' : '' ?>>Hari Ini</option>
                                <option value="bulan" <?= $filter == 'bulan' ? 'selected' : '' ?>>Bulan Ini</option>
                                <option value="tahun" <?= $filter == 'tahun' ? 'selected' : '' ?>>Tahun Ini</option>
                                <option value="semua" <?= $filter == 'semua' ? 'selected' : '' ?>>Semua</option>
                                <option value="tanggal" <?= $filter == 'tanggal' ? 'selected' : '' ?>>Pilih Tanggal</option>
                            </select>
                            <input type="date" name="tanggal" id="tanggal" value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : '' ?>" <?= $filter == 'tanggal' ? '' : 'style="display:none;"' ?>>
                            <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
                        </form>
                    </div>

<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-receipt me-1"></i> Data orders</h4>
        <div>
            <a href="logic/download-orders-pdf.php" class="btn btn-success" target="_blank">Download Data Pesanan (PDF)</a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Pesanan berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Pesanan berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php /* if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Pesanan berhasil ditambah!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; */ ?>


    <table id="datatablesSimple" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pesanan</th>
                <th>Kasir/Pengguna</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Pesanan</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Detail</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result_orders) > 0) {
                while ($order = mysqli_fetch_assoc($result_orders)) {
                    $statusBadge = '';
                    switch ($order['status']) {
                        case 'completed':
                            $statusBadge = 'success';
                            break;
                        case 'pending':
                            $statusBadge = 'warning';
                            break;
                        case 'cancelled':
                            $statusBadge = 'danger';
                            break;
                    }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= htmlspecialchars($order['kasir_username']) ?> (ID: <?= $order['user_id'] ?>)</td>
                        <td><?= htmlspecialchars($order['customer_name'] ?: 'N/A') ?></td>
                        <td><?= $order['order_date'] ?></td>
                        <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($order['payment_method']) ?></td>
                        <td><span class="badge bg-<?= $statusBadge ?>"><?= htmlspecialchars(ucfirst($order['status'])) ?></span></td>
                        <td>
                            <button class="order-detail-toggle btn btn-sm btn-info" data-order-id="<?= $order['order_id'] ?>">Lihat Detail</button>
                        </td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $order['order_id'] ?>">Edit</a>
                            <a href="logic/delete-order.php?id=<?= $order['order_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus pesanan ini?')">Hapus</a>
                        </td>
                    </tr>
                    <tr class="order-details-row" id="details-<?= $order['order_id'] ?>">
                        <td colspan="10">
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
                                                            detail_pembayaran od
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
                                            $image_path = '../../asset/' . htmlspecialchars($item_detail['url_foto']);
                                            // Periksa apakah file gambar ada
                                            if (!file_exists($image_path) || empty($item_detail['url_foto'])) {
                                                $image_path = '../../asset/placeholder.png'; // Ganti dengan path gambar placeholder default Anda
                                            }
                                            ?>
                                            <li>
                                                <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($item_detail['menu_nama']) ?>" width="30" height="30" style="vertical-align: middle; border-radius: 3px; margin-right: 5px;">
                                                <?= htmlspecialchars($item_detail['menu_nama']) ?> (<?= $item_detail['quantity'] ?>x) @ Rp<?= number_format($item_detail['price_per_item'], 0, ',', '.') ?> = Rp<?= number_format($item_detail['subtotal'], 0, ',', '.') ?>
                                                <?php if (!empty($item_detail['item_notes'])): ?>
                                                    <br><small>Catatan Item: <?= htmlspecialchars($item_detail['item_notes']) ?></small>
                                                <?php endif; ?>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        echo "<li>Tidak ada detail item untuk pesanan ini.</li>";
                                    }
                                    $stmt_order_details->close();
                                    ?>
                                </ul>
                                <?php if (!empty($order['notes'])): ?>
                                    <p><strong>Catatan Pesanan Umum:</strong> <?= htmlspecialchars($order['notes']) ?></p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $order['order_id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $order['order_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="logic/edit-order.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $order['order_id'] ?>">Edit Pesanan #<?= $order['order_id'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Kasir/Pengguna (ID)</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['kasir_username']) ?>" readonly>
                                            <input type="hidden" name="user_id" value="<?= $order['user_id'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Pelanggan</label>
                                            <input type="text" name="customer_name" class="form-control" value="<?= htmlspecialchars($order['customer_name']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Pesanan</label>
                                            <input type="datetime-local" name="order_date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($order['order_date'])) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Total Harga</label>
                                            <input type="text" name="total_amount" class="form-control" value="<?= htmlspecialchars($order['total_amount']) ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Metode Pembayaran</label>
                                            <select name="payment_method" class="form-select">
                                                <option value="cash" <?= $order['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                                                <option value="card" <?= $order['payment_method'] === 'card' ? 'selected' : '' ?>>Card</option>
                                                <option value="e-wallet" <?= $order['payment_method'] === 'e-wallet' ? 'selected' : '' ?>>E-Wallet</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan Pesanan Umum</label>
                                            <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($order['notes'] ?: '') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>Tidak ada pesanan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</main>
<?php include '../../views/admin/footer.php'; ?>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Skrip untuk menampilkan/menyembunyikan detail pesanan
        document.querySelectorAll('.order-detail-toggle').forEach(button => {
            button.addEventListener('click', function () {
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
            });
        });

        // Tampilkan input tanggal jika filter "tanggal" dipilih
        document.getElementById('filter_waktu').addEventListener('change', function() {
            document.getElementById('tanggal').style.display = (this.value === 'tanggal') ? '' : 'none';
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>