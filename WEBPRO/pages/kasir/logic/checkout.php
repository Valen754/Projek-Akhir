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

$allowed_order_types = ['Dine In', 'Take Away']; // Match actual names from order_types table
$order_type_name = $_POST['jenis_order'] ?? 'Dine In'; // Renamed variable for clarity

if (!in_array($order_type_name, $allowed_order_types)) {
    $order_type_name = 'Dine In';
}

$allowed_payment_methods = ['Cash', 'E-Wallet', 'QRIS']; // Match actual names from payment_methods table
$payment_method_name = $_POST['payment_method'] ?? 'Cash'; // Renamed variable for clarity
if (!in_array($payment_method_name, $allowed_payment_methods)) {
    $payment_method_name = 'Cash';
}
// Kolom 'notes' tidak ada di tabel 'pembayaran'
$notes = $_POST['notes'] ?? ''; // Variabel ini tetap ada jika digunakan di tempat lain di PHP, tetapi tidak akan diINSERT ke DB 'pembayaran'

$status_name = 'Completed'; // Default status name for new orders, match actual name from payment_status table

$cart = $_SESSION['cart'];
$total_amount = 0; // Total amount tetap dihitung untuk kebutuhan logic/struk, tetapi tidak akan disimpan di tabel 'pembayaran'
foreach ($cart as $item) {
    $total_amount += $item['subtotal'];
}
$order_date = date('Y-m-d H:i:s');

try {
    // Get status_id from payment_status table
    $query_status_id = "SELECT id FROM payment_status WHERE status_name = ?";
    $stmt_status = $conn->prepare($query_status_id);
    if (!$stmt_status) { throw new Exception('Error preparing status statement: ' . $conn->error); }
    $stmt_status->bind_param("s", $status_name);
    $stmt_status->execute();
    $result_status = $stmt_status->get_result();
    if ($result_status && $row_status = $result_status->fetch_assoc()) {
        $status_id = $row_status['id'];
    } else { throw new Exception('Payment status not found.'); }
    $stmt_status->close();

    // Get payment_method_id from payment_methods table
    $query_method_id = "SELECT id FROM payment_methods WHERE method_name = ?";
    $stmt_method = $conn->prepare($query_method_id);
    if (!$stmt_method) { throw new Exception('Error preparing payment method statement: ' . $conn->error); }
    $stmt_method->bind_param("s", $payment_method_name);
    $stmt_method->execute();
    $result_method = $stmt_method->get_result();
    if ($result_method && $row_method = $result_method->fetch_assoc()) {
        $payment_method_id = $row_method['id'];
    } else { throw new Exception('Payment method not found.'); }
    $stmt_method->close();

    // Get order_type_id from order_types table
    $query_order_type_id = "SELECT id FROM order_types WHERE type_name = ?";
    $stmt_order_type = $conn->prepare($query_order_type_id);
    if (!$stmt_order_type) { throw new Exception('Error preparing order type statement: ' . $conn->error); }
    $stmt_order_type->bind_param("s", $order_type_name);
    $stmt_order_type->execute();
    $result_order_type = $stmt_order_type->get_result();
    if ($result_order_type && $row_order_type = $result_order_type->fetch_assoc()) {
        $order_type_id = $row_order_type['id'];
    } else { throw new Exception('Order type not found.'); }
    $stmt_order_type->close();

    // Start transaction
    $conn->begin_transaction();

    // Simpan ke tabel pembayaran menggunakan FK IDs
    // Kolom 'total_amount', 'customer_name', dan 'notes' dihapus dari INSERT statement
    $stmt = $conn->prepare("INSERT INTO pembayaran (user_id, order_date, status_id, payment_method_id, order_type_id) VALUES (?, ?, ?, ?, ?)");
    // Sesuaikan bind_param: i (user_id), s (order_date), i (status_id), i (payment_method_id), i (order_type_id)
    $stmt->bind_param("isiii", $user_id, $order_date, $status_id, $payment_method_id, $order_type_id);

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
        $conn->commit(); // Commit transaction
    } else {
        throw new Exception('Gagal menyimpan pembayaran: ' . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    if ($conn->in_transaction) {
        $conn->rollback(); // Rollback on error
    }
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>