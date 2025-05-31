<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Ambil data menu dengan stok kurang dari 10
$menu_low_stock = [];
$result_menu = mysqli_query($conn, "SELECT url_foto, nama, quantity FROM menu WHERE quantity < 10");
while ($row_menu = mysqli_fetch_assoc($result_menu)) {
    $menu_low_stock[] = $row_menu;
}
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Notifikasi Stok Menu Hampir Habis</h1>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Notifikasi Stok Menu
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($menu_low_stock)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Menu berikut stoknya hampir habis (kurang dari 10):
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-warning">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama Menu</th>
                                    <th>Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menu_low_stock as $i => $menu): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <?php if (!empty($menu['url_foto'])): ?>
                                                <img src="../../asset/<?= htmlspecialchars($menu['url_foto']) ?>" alt="<?= htmlspecialchars($menu['nama']) ?>" style="width:60px; height:60px; object-fit:cover;">
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($menu['nama']) ?></td>
                                        <td><span class="badge bg-danger"><?= $menu['quantity'] ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success" role="alert">
                        Semua stok menu aman (>= 10).
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<!-- Bootstrap JS (wajib untuk dropdown, alert, dsb) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../../views/admin/footer.php'; ?>