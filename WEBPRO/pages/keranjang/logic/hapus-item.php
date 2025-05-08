<?php
include '../../../koneksi.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query = "DELETE FROM keranjang WHERE order_id = $order_id";
    $hapus = mysqli_query($conn, $query);

    if ($hapus) {
        header("Location: ../keranjang.php?status=success");
    } else {
        header("Location: ../keranjang.php?status=error");
    }
    exit();
} else {
    header("Location: ../keranjang.php?status=error");
    exit();
}
