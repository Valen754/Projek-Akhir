<?php
session_start();
include '../../../koneksi.php';

$response = ['success' => false, 'message' => 'Terjadi kesalahan'];

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    $response['message'] = 'Keranjang kosong!';
    header("Location: ../kasir.php?error_msg=" . urlencode($response['message'])); // Redirect jika keranjang kosong
    exit;
}

// Ambil user_id dari session login kasir
$user_id = $_SESSION['user_id'] ?? null;

$customer_name = $_POST['customer_name'] ?? ''; 

// MENGUBAH: $allowed_order_types agar sesuai dengan nama di tabel order_types (huruf kecil, underscore)
$allowed_order_types = ['dine_in', 'take_away', 'delivery']; 
$order_type_name = $_POST['jenis_order'] ?? 'dine_in'; 

if (!in_array($order_type_name, $allowed_order_types)) {
    // Fallback jika nilai yang diterima tidak ada dalam daftar yang diizinkan
    $order_type_name = 'dine_in'; 
}

// MENGUBAH: $allowed_payment_methods agar sesuai dengan nama di tabel payment_methods (huruf besar, spasi)
// (Berdasarkan praktik umum Bootstrap, meskipun tidak ada gambar untuk tabel payment_methods)
$allowed_payment_methods = ['Cash', 'E-Wallet', 'QRIS']; 
$payment_method_name = $_POST['payment_method'] ?? 'Cash'; 
if (!in_array($payment_method_name, $allowed_payment_methods)) {
    $payment_method_name = 'Cash';
}

$notes = $_POST['notes'] ?? ''; 
$status_name = 'Completed'; // Status default untuk pesanan baru

$cart = $_SESSION['cart'];
$total_amount = 0; 
foreach ($cart as $item) {
    $total_amount += $item['subtotal'];
}
$order_date = date('Y-m-d H:i:s');

try {
    // Dapatkan status_id dari tabel payment_status
    $query_status_id = "SELECT id FROM payment_status WHERE status_name = ?";
    $stmt_status = $conn->prepare($query_status_id);
    if (!$stmt_status) { throw new Exception('Error preparing status statement: ' . $conn->error); }
    $stmt_status->bind_param("s", $status_name);
    $stmt_status->execute();
    $result_status = $stmt_status->get_result();
    if ($result_status && $row_status = $result_status->fetch_assoc()) {
        $status_id = $row_status['id'];
    } else { throw new Exception('Payment status not found in DB. Make sure "Completed" exists.'); }
    $stmt_status->close();

    // Dapatkan payment_method_id dari tabel payment_methods
    $query_method_id = "SELECT id FROM payment_methods WHERE method_name = ?";
    $stmt_method = $conn->prepare($query_method_id);
    if (!$stmt_method) { throw new Exception('Error preparing payment method statement: ' . $conn->error); }
    $stmt_method->bind_param("s", $payment_method_name);
    $stmt_method->execute();
    $result_method = $stmt_method->get_result();
    if ($result_method && $row_method = $result_method->fetch_assoc()) {
        $payment_method_id = $row_method['id'];
    } else { throw new Exception('Payment method "' . $payment_method_name . '" not found in DB.'); }
    $stmt_method->close();

    // Dapatkan order_type_id dari tabel order_types
    $query_order_type_id = "SELECT id FROM order_types WHERE type_name = ?";
    $stmt_order_type = $conn->prepare($query_order_type_id);
    if (!$stmt_order_type) { throw new Exception('Error preparing order type statement: ' . $conn->error); }
    $stmt_order_type->bind_param("s", $order_type_name);
    $stmt_order_type->execute();
    $result_order_type = $stmt_order_type->get_result();
    if ($result_order_type && $row_order_type = $result_order_type->fetch_assoc()) {
        $order_type_id = $row_order_type['id'];
    } else { throw new Exception('Order type "' . $order_type_name . '" not found in DB.'); }
    $stmt_order_type->close();

    // Mulai transaksi
    $conn->begin_transaction();

    // Simpan ke tabel pembayaran menggunakan FK IDs
    $stmt = $conn->prepare("INSERT INTO pembayaran (user_id, order_date, status_id, payment_method_id, order_type_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiii", $user_id, $order_date, $status_id, $payment_method_id, $order_type_id);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        // Simpan detail pembayaran
        $stmt_detail = $conn->prepare("INSERT INTO detail_pembayaran (pembayaran_id, menu_id, quantity, price_per_item, item_notes) VALUES (?, ?, ?, ?, ?)");
        foreach ($cart as $item) {
            $stmt_detail->bind_param("iiids", $order_id, $item['menu_id'], $item['quantity'], $item['price_per_item'], $item['item_notes']);
            $stmt_detail->execute();
        }
        $stmt_detail->close();
        unset($_SESSION['cart']); // kosongkan keranjang
        
        $conn->commit(); // Commit transaksi
        // Redirect setelah berhasil
        header("Location: ../struk.php?id=" . $order_id);
        exit();
    } else {
        throw new Exception('Gagal menyimpan pembayaran: ' . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    if ($conn->in_transaction) {
        $conn->rollback(); // Rollback saat terjadi error
    }
    // Redirect dengan pesan error
    header("Location: ../kasir.php?error_msg=" . urlencode($e->getMessage()));
    exit();
}