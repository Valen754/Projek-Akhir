<?php
include '../../../koneksi.php';
session_start();

try {
    // Validate input
    if (!isset($_POST['customer_name'], $_POST['jenis_order'], $_POST['payment_method'], $_POST['items'])) {
        throw new Exception('Missing required fields');
    }

    $customer_name = htmlspecialchars($_POST['customer_name']);
    $jenis_order_name = htmlspecialchars($_POST['jenis_order']); // Renamed variable
    $payment_method_name = htmlspecialchars($_POST['payment_method']); // Renamed variable
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

    // Get status_id from payment_status table
    $status_name = 'Completed'; // Default status for new orders
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
    $stmt_order_type->bind_param("s", $jenis_order_name);
    $stmt_order_type->execute();
    $result_order_type = $stmt_order_type->get_result();
    if ($result_order_type && $row_order_type = $result_order_type->fetch_assoc()) {
        $order_type_id = $row_order_type['id'];
    } else { throw new Exception('Order type not found.'); }
    $stmt_order_type->close();


    // Insert pembayaran using foreign key IDs
    $sql = "INSERT INTO pembayaran (user_id, order_date, status_id, payment_method_id, order_type_id) 
             VALUES (?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error preparing pembayaran statement: ' . $conn->error);
    }
    // Bind parameters: i (user_id), i (status_id), i (payment_method_id), i (order_type_id)
    $stmt->bind_param("iiii", $user_id, $status_id, $payment_method_id, $order_type_id);
    if (!$stmt->execute()) {
        throw new Exception('Error executing pembayaran insert: ' . $stmt->error);
    }
    $order_id = $conn->insert_id;

    // Insert detail pembayaran (tanpa update stok)
    $sql_items = "INSERT INTO detail_pembayaran (pembayaran_id, menu_id, quantity, price_per_item, item_notes) VALUES (?, ?, ?, ?, ?)";
    // $sql_stock = "UPDATE menu SET quantity = quantity - ? WHERE id = ?"; // <-- DIKOMENTARI: KOLOM 'QUANTITY' TIDAK ADA DI TABEL 'MENU'

    $stmt_items = $conn->prepare($sql_items);
    if (!$stmt_items) {
        throw new Exception('Error preparing detail_pembayaran statement: ' . $conn->error);
    }
    // $stmt_stock = $conn->prepare($sql_stock); // <-- DIKOMENTARI

    foreach ($items as $item) {
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