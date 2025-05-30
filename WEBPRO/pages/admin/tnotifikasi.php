<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Query SQL untuk mengambil data notifikasi
// Melakukan JOIN dengan tabel users, orders, dan reservasi untuk mendapatkan detail
$sql = "SELECT 
            n.id, 
            n.user_id, 
            u.username, 
            n.order_id, 
            o.total_amount AS order_total_amount,
            n.reservation_id, 
            r.kode_reservasi AS reservation_code,
            n.type, 
            n.pesan, 
            n.is_read, 
            n.created_at
        FROM 
            notifikasi n
        LEFT JOIN 
            users u ON n.user_id = u.id
        LEFT JOIN 
            orders o ON n.order_id = o.id
        LEFT JOIN 
            reservasi r ON n.reservation_id = r.id
        ORDER BY 
            n.created_at DESC";

$result = mysqli_query($conn, $sql);

// Cek menu dengan stok kurang dari 10
$menu_low_stock = [];
$result_menu = mysqli_query($conn, "SELECT nama, quantity FROM menu WHERE quantity < 10");
while ($row_menu = mysqli_fetch_assoc($result_menu)) {
    $menu_low_stock[] = $row_menu;
}
?>

<h1 class="mt-4">Notifikasi</h1>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Data Notifikasi
                        </div>
                    </div>

<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-bell me-1"></i> Data Notifikasi</h4>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'read'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Notifikasi berhasil ditandai sudah dibaca!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($menu_low_stock)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Perhatian!</strong> Menu berikut stoknya hampir habis (kurang dari 9):<br>
            <ul style="margin-bottom:0;">
                <?php foreach ($menu_low_stock as $menu): ?>
                    <li><?= htmlspecialchars($menu['nama']) ?> (Sisa: <?= $menu['quantity'] ?>)</li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Pengirim (User ID)</th>
                <th>Pesan</th>
                <th>Terkait Order ID</th>
                <th>Terkait Reservasi ID</th>
                <th>Status Baca</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $ada_notifikasi = false;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $ada_notifikasi = true;
                    $isReadStatus = $row['is_read'] ? 'Sudah Dibaca' : 'Belum Dibaca';
                    $isReadBadge = $row['is_read'] ? 'secondary' : 'info'; // Warna badge
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars(ucfirst($row['type'] ?? 'N/A')) ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? 'N/A') ?> (ID: <?= $row['user_id'] ?? 'N/A' ?>)</td>
                        <td><?= htmlspecialchars($row['pesan'] ?? 'N/A') ?></td>
                        <td>
                            <?php if ($row['order_id']): ?>
                                Order #<?= $row['order_id'] ?> (Total: Rp<?= number_format($row['order_total_amount'], 0, ',', '.') ?>)
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['reservation_id']): ?>
                                Reservasi #<?= htmlspecialchars($row['reservation_code']) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-<?= $isReadBadge ?>"><?= $isReadStatus ?></span></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <?php if (!$row['is_read']): ?>
                                <a href="logic/mark-notification-read.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Tandai Dibaca</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>Dibaca</button>
                            <?php endif; ?>
                            <a href="logic/delete-notification.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus notifikasi ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php
                }
            }
            // Tampilkan pesan hanya jika tidak ada notifikasi sama sekali
            if (!$ada_notifikasi && empty($menu_low_stock)) {
                echo "<tr><td colspan='9' class='text-center'>Tidak ada notifikasi.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</main>
<?php include '../../views/admin/footer.php'; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/table-menu.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>