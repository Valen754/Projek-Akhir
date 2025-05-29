<?php

include '../../views/admin/header.php';
include '../../views/admin/sidebar.php';
include '../../koneksi.php';

// Hitung total pendapatan
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders"))['total'];
$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
// Hanya hitung user dengan role 'pelanggan'
$total_pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'member'"))['total'];
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
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../../views/admin/footer.php'; ?>