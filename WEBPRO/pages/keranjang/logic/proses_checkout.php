<?php
include '../../../koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_ids = $_POST['checkout_items'] ?? [];

if (empty($order_ids)) {
    echo "Tidak ada item yang dipilih untuk checkout.";
    exit();
}

// Ubah array ID menjadi string dipisahkan koma
$order_ids_str = implode(",", array_map('intval', $order_ids));

// Ambil total harga dari item yang dipilih
$sql = "SELECT SUM(price) AS total FROM keranjang WHERE order_id IN ($order_ids_str) AND user_id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_price = $row['total'];

// Simpan ke tabel checkout
$insert = mysqli_query($conn, "INSERT INTO checkout (user_id, order_ids, total_price) VALUES ($user_id, '$order_ids_str', $total_price)");

if ($insert) {
    // Hapus dari keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE order_id IN ($order_ids_str) AND user_id = $user_id");

    // Redirect ke halaman sukses atau pesan
    header("Location: ../checkout_success.php");
    exit();
} else {
    echo "Gagal melakukan checkout.";
}
?>
