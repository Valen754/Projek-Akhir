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

// Cek apakah user_id valid
$user_check = mysqli_query($conn, "SELECT id FROM users WHERE id = $user_id");
if (mysqli_num_rows($user_check) == 0) {
    echo "User tidak ditemukan di tabel users.";
    exit();
}

// Ambil data menu_id dan price dari keranjang
$order_ids_str = implode(",", array_map('intval', $order_ids));
$sql = "SELECT order_id, menu_id, price FROM keranjang WHERE order_id IN ($order_ids_str) AND user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Data keranjang tidak ditemukan.";
    exit();
}

$total_price = 0;
$success = true;

while ($row = mysqli_fetch_assoc($result)) {
    $menu_id = intval($row['menu_id']);
    $price = floatval($row['price']);
    $total_price += $price;

    // Insert satu per satu ke tabel checkout
    $insert = mysqli_query($conn, "INSERT INTO checkout (user_id, menu_id, total_price) VALUES ($user_id, $menu_id, $price)");
    if (!$insert) {
        $success = false;
        break;
    }
}

if ($success) {
    // Hapus dari keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE order_id IN ($order_ids_str) AND user_id = $user_id");

    // Redirect ke halaman sukses atau pesan
    header("Location: ../checkout_success.php");
    exit();
} else {
    echo "Gagal melakukan checkout.";
}
?>
