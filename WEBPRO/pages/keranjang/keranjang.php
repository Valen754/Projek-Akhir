<?php
session_start();
include '../../koneksi.php'; // Pastikan jalur ini benar
include '../../views/header.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT keranjang.*, menu.nama FROM keranjang JOIN menu ON keranjang.menu_id = menu.id WHERE keranjang.user_id = $user_id");
$total = 0;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Keranjang</title>
    <link rel="stylesheet" href="../../css/keranjang.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- BREADCRUMB -->
    <div class="wadah-breadcrumb">
        <nav class="navigasi-breadcrumb" aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li><a href="menu.html">Menu</a></li>
                <li class="aktif">Cart</li>
            </ul>
        </nav>
    </div>

    <!-- Table -->
    <div class="wadah">
        <h3 class="judul">Shopping Cart</h3>

        <div class="tabel-wadah">
            <table class="tabel">
            <table border="1" class="tabel">
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['catatan'] ?></td>
                    <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                    <td><a href="hapus_item.php?id=<?= $row['id'] ?>">Hapus</a></td>
                </tr>
                <?php $total += $row['price']; } ?>
            </table>
        </div>
        <p>Total: <strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
            <a href="checkout.php">Checkout</a>
    </div>

    <script src="keranjang.js"></script>
</body>

</html>