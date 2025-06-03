<?php
session_start();
include '../../../koneksi.php';

// Ambil data dari form
$user_id = $_SESSION['user_id']; // pastikan ini sudah di-set saat login kasir
$customer_name = $_POST['customer_name'] ?? '';
$payment_method = $_POST['payment_method'] ?? 'cash';
$voucher = $_POST['voucher_code'] ?? '';
$cart = $_SESSION['cart'] ?? [];

if (!$cart || count($cart) == 0) {
    echo json_encode(['success' => false, 'message' => 'Keranjang kosong!']);
    exit;
}

// Kalkulasi subtotal
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['subtotal'];
}

// [Opsional] Hitung diskon dari kode voucher
$diskon = 0;
if ($voucher == "TAPALKUDA10") { // contoh
    $diskon = 0.10 * $subtotal;
}

// Pajak 10%
$pajak = 0.10 * ($subtotal - $diskon);

// Hitung total akhir
$total = $subtotal - $diskon + $pajak;

// Simpan ke `orders`
mysqli_query($conn, "INSERT INTO orders (user_id, order_date, total_amount, status, customer_name, payment_method) VALUES (
    '$user_id', NOW(), '$total', 'completed', '".mysqli_real_escape_string($conn, $customer_name)."', '".mysqli_real_escape_string($conn, $payment_method)."'
)");
$order_id = mysqli_insert_id($conn);

// Simpan ke order_details
foreach ($cart as $item) {
    mysqli_query($conn, "INSERT INTO order_details (order_id, menu_id, quantity, price_per_item, subtotal, item_notes) VALUES (
        '$order_id',
        '{$item['menu_id']}',
        '{$item['quantity']}',
        '{$item['price_per_item']}',
        '{$item['subtotal']}',
        '".mysqli_real_escape_string($conn, $item['item_notes'])."'
    )");
}

// Kosongkan keranjang
unset($_SESSION['cart']);

echo json_encode(['success' => true, 'order_id' => $order_id]);
