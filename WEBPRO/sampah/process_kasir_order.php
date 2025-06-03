<?php
include '../../../koneksi.php'; // Sesuaikan path koneksi Anda
session_start();

header('Content-Type: application/json'); // Penting untuk respons JSON

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    $response['message'] = 'Akses ditolak. Hanya kasir yang dapat memproses pesanan.';
    echo json_encode($response);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $response['message'] = 'Data tidak valid.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id']; // ID kasir yang memproses
$customer_name = $input['customer_name'] ?? 'Pelanggan Umum'; // Nama pelanggan dari input
$payment_method = $input['payment_method'] ?? 'cash';
$order_notes = $input['order_notes'] ?? '';
$total_amount = $input['total_amount'] ?? 0;
$items = $input['items'] ?? [];

if (empty($items)) {
    $response['message'] = 'Tidak ada item dalam pesanan.';
    echo json_encode($response);
    exit();
}

$conn->begin_transaction(); // Mulai transaksi

try {
    // 1. Masukkan ke tabel 'orders'
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, customer_name, payment_method, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt_order->bind_param("idsss", $user_id, $total_amount, $customer_name, $payment_method, $order_notes);
    $stmt_order->execute();
    $order_id_new = $conn->insert_id; // Dapatkan ID pesanan yang baru dibuat
    $stmt_order->close();

    // 2. Masukkan detail ke tabel 'order_details' dan update stok menu
    $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, menu_id, quantity, price_per_item, subtotal, item_notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_update_stock = $conn->prepare("UPDATE menu SET quantity = quantity - ? WHERE id = ?");

    foreach ($items as $item) {
        $menu_id = $item['id']; // ID menu dari data JS
        $quantity = $item['qty'];
        $price_per_item = $item['price'];
        $subtotal_item = $quantity * $price_per_item;
        $item_notes = $item['item_notes'] ?? '';

        // Cek stok sebelum mengurangi
        $check_stock_stmt = $conn->prepare("SELECT quantity FROM menu WHERE id = ?");
        $check_stock_stmt->bind_param("i", $menu_id);
        $check_stock_stmt->execute();
        $stock_result = $check_stock_stmt->get_result();
        $current_stock = $stock_result->fetch_assoc()['quantity'];
        $check_stock_stmt->close();

        if ($current_stock < $quantity) {
            throw new Exception("Stok untuk " . htmlspecialchars($item['name']) . " tidak mencukupi. Tersedia: " . $current_stock);
        }

        $stmt_detail->bind_param(
            "iiidss",
            $order_id_new,
            $menu_id,
            $quantity,
            $price_per_item,
            $subtotal_item,
            $item_notes
        );
        $stmt_detail->execute();

        // Update stok menu
        $stmt_update_stock->bind_param("ii", $quantity, $menu_id);
        $stmt_update_stock->execute();
    }
    $stmt_detail->close();
    $stmt_update_stock->close();

    // Jika semua berhasil, commit transaksi
    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Pesanan berhasil diproses!';

} catch (Exception $e) {
    $conn->rollback(); // Rollback jika ada kesalahan
    $response['message'] = 'Gagal memproses pesanan: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>