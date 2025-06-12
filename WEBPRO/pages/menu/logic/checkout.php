<?php
include '../../../koneksi.php';
session_start();

try {
    // Validate input
    if (!isset($_POST['customer_name'], $_POST['jenis_order'], $_POST['payment_method'], $_POST['items'])) {
        throw new Exception('Missing required fields');
    }

    $customer_name = htmlspecialchars($_POST['customer_name']);
    $jenis_order = htmlspecialchars($_POST['jenis_order']);
    $payment_method = htmlspecialchars($_POST['payment_method']);
    $items = json_decode($_POST['items'], true);

    if (!$items || empty($items)) {
        throw new Exception('No items in cart');
    }

    // Begin transaction to ensure data consistency
    $conn->begin_transaction();

    // --- BAGIAN VALIDASI STOK DIKOMENTARI KARENA KOLOM 'QUANTITY' TIDAK ADA DI TABEL 'MENU' PADA SKEMA DB YANG DIBERIKAN ---
    // $subtotal = 0;
    // $stock_errors = [];

    // foreach ($items as $item) {
    //     // Get current stock level
    //     $stmt = $conn->prepare("SELECT quantity FROM menu WHERE id = ?");
    //     $stmt->bind_param("i", $item['id']);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $current_stock = $result->fetch_assoc()['quantity'];

    //     if ($current_stock < $item['quantity']) {
    //         $stock_errors[] = "Stok untuk {$item['name']} tidak mencukupi. Tersedia: $current_stock";
    //         continue;
    //     }
    //     $subtotal += $item['price'] * $item['quantity'];
    // }

    // if (!empty($stock_errors)) {
    //     $conn->rollback();
    //     throw new Exception(implode("\n", $stock_errors));
    // }
    // --- AKHIR BAGIAN VALIDASI STOK YANG DIKOMENTARI ---

    // Menghitung subtotal dari item yang ada di keranjang (tanpa validasi stok dari DB)
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }


    $tax = round($subtotal * 0.10);
    $total = $subtotal + $tax;

    // Get user_id from session
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception('User not logged in');
    }

    // Insert pembayaran
    $status = 'completed';   // Default status for new orders
    // Kolom 'notes' tidak ada di tabel 'pembayaran', sehingga dihapus
    // Kolom 'total_amount' dan 'customer_name' juga tidak ada, dihapus
    $sql = "INSERT INTO pembayaran (user_id, order_date, status, payment_method, order_type) 
             VALUES (?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error preparing pembayaran statement: ' . $conn->error);
    }
    // Sesuaikan bind_param: i (user_id), s (status), s (payment_method), s (order_type)
    $stmt->bind_param("isss", $user_id, $status, $payment_method, $jenis_order);
    if (!$stmt->execute()) {
        throw new Exception('Error executing pembayaran insert: ' . $stmt->error);
    }
    $order_id = $conn->insert_id;

    // Insert detail pembayaran (tanpa update stok)
    // Kolom 'order_id' diubah menjadi 'pembayaran_id'
    // Kolom 'subtotal' dihapus karena tidak ada di tabel 'detail_pembayaran'
    $sql_items = "INSERT INTO detail_pembayaran (pembayaran_id, menu_id, quantity, price_per_item, item_notes) VALUES (?, ?, ?, ?, ?)";
    // $sql_stock = "UPDATE menu SET quantity = quantity - ? WHERE id = ?"; // <-- DIKOMENTARI: KOLOM 'QUANTITY' TIDAK ADA DI TABEL 'MENU'

    $stmt_items = $conn->prepare($sql_items);
    if (!$stmt_items) {
        throw new Exception('Error preparing detail_pembayaran statement: ' . $conn->error);
    }
    // $stmt_stock = $conn->prepare($sql_stock); // <-- DIKOMENTARI

    foreach ($items as $item) {
        // $item_subtotal = $item['price'] * $item['quantity']; // Dihapus karena tidak ada kolom 'subtotal' di detail_pembayaran
        $item_notes = $item['note'] ?? ''; // Menggunakan 'note' dari JS, fallback ke string kosong

        // Insert order item
        // Sesuaikan bind_param: i (pembayaran_id), i (menu_id), i (quantity), d (price_per_item), s (item_notes)
        $stmt_items->bind_param(
            "iiids",
            $order_id,
            $item['id'],
            $item['quantity'],
            $item['price'],
            $item_notes
        );
        if (!$stmt_items->execute()) {
            throw new Exception('Error inserting detail_pembayaran: ' . $stmt_items->error . ' for item: ' . $item['name']);
        }

        // --- BAGIAN UPDATE STOK DIKOMENTARI KARENA KOLOM 'QUANTITY' TIDAK ADA DI TABEL 'MENU' PADA SKEMA DB YANG DIBERIKAN ---
        // Update stock
        // $stmt_stock->bind_param("ii", $item['quantity'], $item['id']);
        // if (!$stmt_stock->execute()) {
        //     throw new Exception('Error updating stock: ' . $stmt_stock->error . ' for item: ' . $item['name']);
        // }
        // --- AKHIR BAGIAN UPDATE STOK YANG DIKOMENTARI ---
    }

    // Commit transaction
    $conn->commit();

    // Redirect with JavaScript to clear sessionStorage and show receipt
    echo "<script>
        sessionStorage.removeItem('checkout_items');
        window.location.href = '../struk.php?id=" . $order_id . "';
    </script>";
    exit();

} catch (Exception $e) {
    // Rollback on error
    if (isset($conn) && $conn->connect_errno == 0) {
        $conn->rollback();
    }

    echo "<script>
        alert('Error: " . addslashes($e->getMessage()) . "');
        window.location.href = '../menu.php';
    </script>";
}
?>