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

// Kolom 'customer_name' tidak ada di tabel 'pembayaran'
$customer_name = $_POST['customer_name'] ?? ''; // Variabel ini tetap ada jika digunakan di tempat lain di PHP, tetapi tidak akan diINSERT ke DB 'pembayaran'

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
// Kolom 'notes' tidak ada di tabel 'pembayaran'
$notes = $_POST['notes'] ?? ''; // Variabel ini tetap ada jika digunakan di tempat lain di PHP, tetapi tidak akan diINSERT ke DB 'pembayaran'

$status = 'completed'; // atau sesuai kebutuhan

$cart = $_SESSION['cart'];
$total_amount = 0; // Total amount tetap dihitung untuk kebutuhan logic/struk, tetapi tidak akan disimpan di tabel 'pembayaran'
foreach ($cart as $item) {
    $total_amount += $item['subtotal'];
}
$order_date = date('Y-m-d H:i:s');

// Simpan ke tabel pembayaran
// Kolom 'total_amount', 'customer_name', dan 'notes' dihapus dari INSERT statement
$stmt = $conn->prepare("INSERT INTO pembayaran (user_id, order_date, status, payment_method, order_type) VALUES (?, ?, ?, ?, ?)");
// Sesuaikan bind_param: i (user_id), s (order_date), s (status), s (payment_method), s (order_type)
$stmt->bind_param("issss", $user_id, $order_date, $status, $payment_method, $order_type);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
    // Simpan detail pembayaran
    // Kolom 'order_id' diubah menjadi 'pembayaran_id'
    // Kolom 'subtotal' dihapus dari INSERT statement
    $stmt_detail = $conn->prepare("INSERT INTO detail_pembayaran (pembayaran_id, menu_id, quantity, price_per_item, item_notes) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        // Sesuaikan bind_param: i (pembayaran_id), i (menu_id), i (quantity), d (price_per_item), s (item_notes)
        $stmt_detail->bind_param("iiids", $order_id, $item['menu_id'], $item['quantity'], $item['price_per_item'], $item['item_notes']);
        $stmt_detail->execute();
    }
    $stmt_detail->close();
    unset($_SESSION['cart']); // kosongkan keranjang
    $response = ['success' => true, 'order_id' => $order_id];
} else {
    $response['message'] = 'Gagal menyimpan pembayaran: ' . $stmt->error; // Tambahkan error detail untuk debugging
}
$stmt->close();
echo json_encode($response);
?>