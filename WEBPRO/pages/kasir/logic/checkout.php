<?php
session_start();
include '../../../koneksi.php';

$response = ['success' => false, 'message' => 'Terjadi kesalahan'];

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    $response['message'] = 'Keranjang kosong!';
    echo json_encode($response);
    exit;
}

// Ambil user_id dari session login kasir
$user_id = $_SESSION['user_id'] ?? null;

$customer_name = $_POST['customer_name'] ?? '';
$allowed_order_types = ['dine_in', 'take_away'];
$order_type = $_POST['jenis_order'] ?? 'dine_in';

if (!in_array($order_type, $allowed_order_types)) {
    $order_type = 'dine_in';
}

$allowed_payment_methods = ['cash', 'e-wallet', 'qris'];
$payment_method = $_POST['payment_method'] ?? 'cash';
if (!in_array($payment_method, $allowed_payment_methods)) {
    $payment_method = 'cash';
}
$notes = $_POST['notes'] ?? '';
$status = 'completed'; // atau sesuai kebutuhan

$cart = $_SESSION['cart'];
$total_amount = 0;
foreach ($cart as $item) {
    $total_amount += $item['subtotal'];
}
$order_date = date('Y-m-d H:i:s');

// Simpan ke tabel pembayaran
$stmt = $conn->prepare("INSERT INTO pembayaran (user_id, order_date, total_amount, status, customer_name, payment_method, order_type, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isdsssss", $user_id, $order_date, $total_amount, $status, $customer_name, $payment_method, $order_type, $notes);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
    // Simpan detail pembayaran
    $stmt_detail = $conn->prepare("INSERT INTO detail_pembayaran (order_id, menu_id, quantity, price_per_item, subtotal, item_notes) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt_detail->bind_param("iiidds", $order_id, $item['menu_id'], $item['quantity'], $item['price_per_item'], $item['subtotal'], $item['item_notes']);
        $stmt_detail->execute();
    }
    $stmt_detail->close();
    unset($_SESSION['cart']); // kosongkan keranjang
    $response = ['success' => true, 'order_id' => $order_id];
} else {
    $response['message'] = 'Gagal menyimpan pembayaran';
}
$stmt->close();
echo json_encode($response);
?>