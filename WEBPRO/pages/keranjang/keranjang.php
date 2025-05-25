<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

include '../../views/header.php';


$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT keranjang.*, menu.nama FROM keranjang JOIN menu ON keranjang.menu_id = menu.id WHERE keranjang.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Keranjang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/keranjang.css">
</head>

<body>
    <div class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../menu/menu.php">Menu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </nav>

        <?php
            //notifikasi berhasil edit keranjang
        if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Keranjang berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h3 class="mb-4">Shopping Cart</h3>

        <form action="logic/proses_checkout.php" method="POST">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><input type="checkbox" name="checkout_items[]" value="<?= $row['order_id'] ?>" class="item-checkbox" data-price="<?= $row['price'] ?>"></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['catatan'] ?></td>
                            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td>
                            <a href="logic/hapus-item.php?order_id=<?= $row['order_id'] ?>" class="btn btn-danger btn-sm">Hapus</a> ||
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editItemModal<?= $row['order_id'] ?>">Edit</a>
                            </td>
                        </tr>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </tbody>
            </table>

            <p>Total: <strong id="totalHarga">Rp 0</strong></p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                Checkout
            </button>



            <!-- ADD Modal -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkoutLabel" style="color: black;">Konfirmasi Checkout</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="color: black;">
                            Apakah Anda yakin ingin checkout barang yang dipilih?
                            <p id="modalTotalHarga" class="mt-2" style="color: black;">Total: <strong>Rp 0</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Checkout Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

                    <!-- Edit Modal -->
                    <?php mysqli_data_seek($query, 0); while ($row = mysqli_fetch_assoc($query)) { ?>
                        <div class="modal fade" id="editItemModal<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="editItemModalLabel<?= $row['order_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="logic/edit-item.php" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="color:black;">Edit Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="color:black;">
                                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                            <div class="mb-3">
                                                <label for="quantity<?= $row['order_id'] ?>">Jumlah</label>
                                                <input type="number" name="quantity" value="<?= $row['quantity'] ?>" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="catatan<?= $row['order_id'] ?>">Catatan</label>
                                                <textarea name="catatan" class="form-control"><?= $row['catatan'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalHarga = document.getElementById('totalHarga');
        const selectAll = document.getElementById('selectAll');
        const modalTotal = document.getElementById('modalTotalHarga');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.price);
                }
            });
            const formatted = 'Rp ' + total.toLocaleString('id-ID');
            totalHarga.innerText = formatted;
            modalTotal.innerHTML = 'Total: <strong>' + formatted + '</strong>';
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));

        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateTotal();
        });
    </script>
</body>

</html>