<?php
include '../../views/admin/header.php';
include '../../views/admin/sidebar.php';
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
        display: none;
        /* Sembunyikan baris detail secara default */
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
            <input type="date" name="tanggal" id="tanggal"
                value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : '' ?>" <?= $filter == 'tanggal' ? '' : 'style="display:none;"' ?>>
            <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
        </form>
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="fas fa-receipt me-1"></i> Data orders</h4>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Pesanan berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?> <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Pesanan berhasil dihapus!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: <?= htmlspecialchars(urldecode($_GET['error'] ?? 'Terjadi kesalahan')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
                            <td><span
                                    class="badge bg-<?= $statusBadge ?>"><?= htmlspecialchars(ucfirst($order['status'])) ?></span>
                            </td>
                            <td>
                                <button class="order-detail-toggle btn btn-sm btn-info"
                                    data-order-id="<?= $order['order_id'] ?>">Lihat Detail</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $order['order_id'] ?>">Edit</button>
                                <a href="#" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete(<?= $order['order_id'] ?>)">Hapus</a>
                            </td>
                        </tr>
                        <tr class="order-details-row" id="details-<?= $order['order_id'] ?>">
                            <td colspan="10">
                                <div class="order-details-content">
                                    <?php
                                    // Query untuk mengambil detail pesanan
                                    $detail_query = "SELECT 
                                    dp.*, 
                                    m.nama as menu_name,
                                    m.price as menu_price
                                    FROM detail_pembayaran dp 
                                    JOIN menu m ON dp.menu_id = m.id 
                                    WHERE dp.order_id = " . $order['order_id'];
                                    $detail_result = mysqli_query($conn, $detail_query);
                                    ?>
                                    <h5>Detail Pesanan #<?= $order['order_id'] ?></h5>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($detail = mysqli_fetch_assoc($detail_result)) { ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($detail['menu_name']) ?></td>
                                                    <td><?= $detail['quantity'] ?></td>
                                                    <td>Rp <?= number_format($detail['menu_price'], 0, ',', '.') ?></td>
                                                    <td>Rp
                                                        <?= number_format($detail['menu_price'] * $detail['quantity'], 0, ',', '.') ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="table-secondary">
                                                <td colspan="3" class="text-end"><strong>Total Pembayaran:</strong></td>
                                                <td><strong>Rp
                                                        <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php if ($order['notes']): ?>
                                        <div class="mt-2">
                                            <strong>Catatan:</strong> <?= htmlspecialchars($order['notes']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr> <!-- Modal Edit -->
                        <div class="modal fade" id="editModal<?= $order['order_id'] ?>" tabindex="-1"
                            aria-labelledby="editModalLabel<?= $order['order_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="logic/edit-orders.php" method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?= $order['order_id'] ?>">Edit Pesanan
                                                #<?= $order['order_id'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">

                                            <div class="mb-3">
                                                <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                                    value="<?= htmlspecialchars($order['customer_name']) ?>">
                                            </div>

                                            <div class="mb-3">
                                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                                <select class="form-select" id="payment_method" name="payment_method">
                                                    <option value="cash" <?= $order['payment_method'] == 'cash' ? 'selected' : '' ?>>Cash</option>
                                                    <option value="qris" <?= $order['payment_method'] == 'qris' ? 'selected' : '' ?>>QRIS</option>
                                                    <option value="debit" <?= $order['payment_method'] == 'debit' ? 'selected' : '' ?>>Debit</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-select" id="status" name="status">
                        
                                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="notes<?= $order['order_id'] ?>" class="form-label">Catatan</label>
                                                <textarea class="form-control" id="notes<?= $order['order_id'] ?>" name="notes"
                                                    rows="3"><?= htmlspecialchars($order['notes']) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pesanan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" class="btn btn-danger" id="deleteConfirmBtn">Hapus</a>
            </div>
        </div>
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
        document.getElementById('filter_waktu').addEventListener('change', function () {
            document.getElementById('tanggal').style.display = (this.value === 'tanggal') ? '' : 'none';
        });
    });

    function confirmDelete(orderId) {
        // Set the correct delete URL
        document.getElementById('deleteConfirmBtn').href = 'logic/delete-orders.php?id=' + orderId;
        // Show the modal
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>