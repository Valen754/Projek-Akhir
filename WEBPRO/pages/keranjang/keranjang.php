<?php
include '../../koneksi.php';
include '../../views/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

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
                            <td><a href="hapus-item.php?order_id=<?= $row['order_id'] ?>" class="btn btn-danger btn-sm">Hapus</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <p>Total: <strong id="totalHarga">Rp 0</strong></p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                Checkout
            </button>

            <!-- Modal -->
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