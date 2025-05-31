<?php

include '../../views/admin/header.php';
include '../../views/admin/sidebar.php';
include '../../koneksi.php';

// Hitung total pendapatan
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders"))['total'];
$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
// Hanya hitung user dengan role 'pelanggan'
$total_pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'member'"))['total'];

// Cek menu dengan stok kurang dari 9
$menu_low_stock = [];
$result_menu = mysqli_query($conn, "SELECT nama, quantity FROM menu WHERE quantity < 10");
while ($row_menu = mysqli_fetch_assoc($result_menu)) {
    $menu_low_stock[] = $row_menu;
}

// Cek reservasi baru (status pending)
$reservasi_baru = [];
$result_reservasi = mysqli_query($conn, "SELECT id, kode_reservasi, email, tanggal_reservasi FROM reservasi WHERE status = 'pending'");
while ($row_reservasi = mysqli_fetch_assoc($result_reservasi)) {
    $reservasi_baru[] = $row_reservasi;
}
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard Admin</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Ringkasan Pesanan</li>
        </ol>
        <div class="row">
            <!-- Total Pendapatan -->
            <div class="col-xl-4 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <h4>Total Pendapatan</h4>
                        <span style="font-size:2.5em;font-weight:bold;">
                            Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?>
                        </span>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="small text-white">Akumulasi seluruh transaksi</span>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
            <!-- Total Pesanan -->
            <div class="col-xl-4 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h4>Total Pesanan</h4>
                        <span style="font-size:2.5em;font-weight:bold;"><?php echo $total_pesanan; ?></span>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="small text-white">Semua pesanan masuk</span>
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
            <!-- Pelanggan Terdaftar -->
            <div class="col-xl-4 col-md-6">
                <div class="card bg-secondary text-white mb-4">
                    <div class="card-body">
                        <h4>Pelanggan Terdaftar</h4>
                        <span style="font-size:2.5em;font-weight:bold;"><?php echo $total_pelanggan; ?></span>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="small text-white">User dengan role pelanggan</span>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($menu_low_stock)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> Menu berikut stoknya hampir habis (kurang dari 10):<br>
                <ul style="margin-bottom:0;">
                    <?php foreach ($menu_low_stock as $menu): ?>
                        <li><?= htmlspecialchars($menu['nama']) ?> (Sisa: <?= $menu['quantity'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($reservasi_baru)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Reservasi Baru!</strong> Ada member yang melakukan reservasi:<br>
                <ul style="margin-bottom:0;">
                    <?php foreach ($reservasi_baru as $res): ?>
                        <li>
                            <?= htmlspecialchars($res['email']) ?> (<?= htmlspecialchars($res['kode_reservasi']) ?>) 
                            pada <?= date('d-m-Y', strtotime($res['tanggal_reservasi'])) ?>
                            <a href="../../pages/admin/treservasi.php?id=<?= $res['id'] ?>" class="btn btn-sm btn-primary ms-2">Lihat Detail</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../../views/admin/footer.php'; ?>