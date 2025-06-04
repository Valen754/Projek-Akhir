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

    // Validate stock levels and calculate totals
    $subtotal = 0;
    $stock_errors = [];

    foreach ($items as $item) {
        // Get current stock level
        $stmt = $conn->prepare("SELECT quantity FROM menu WHERE id = ?");
        $stmt->bind_param("i", $item['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_stock = $result->fetch_assoc()['quantity'];

        if ($current_stock < $item['quantity']) {
            $stock_errors[] = "Stok untuk {$item['name']} tidak mencukupi. Tersedia: $current_stock";
            continue;
        }

        $subtotal += $item['price'] * $item['quantity'];
    }

    if (!empty($stock_errors)) {
        $conn->rollback();
        throw new Exception(implode("\n", $stock_errors));
    }

    $tax = round($subtotal * 0.10);
    $total = $subtotal + $tax;    // Get user_id from session
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception('User not logged in');
    }

    // Insert pembayaran
    $status = 'completed';  // Default status for new orders
    $sql = "INSERT INTO pembayaran (user_id, order_date, total_amount, status, customer_name, payment_method, order_type, notes) 
            VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idsssss", $user_id, $total, $status, $customer_name, $payment_method, $jenis_order, $item['note']);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Insert detail pembayaran and update stock
    $sql_items = "INSERT INTO detail_pembayaran (order_id, menu_id, quantity, price_per_item, subtotal, item_notes) VALUES (?, ?, ?, ?, ?, ?)";
    $sql_stock = "UPDATE menu SET quantity = quantity - ? WHERE id = ?";

    $stmt_items = $conn->prepare($sql_items);
    $stmt_stock = $conn->prepare($sql_stock);

    foreach ($items as $item) {
        $item_subtotal = $item['price'] * $item['quantity'];
        // Insert order item
        $stmt_items->bind_param(
            "iiidds",
            $order_id,
            $item['id'],
            $item['quantity'],
            $item['price'],
            $item_subtotal,
            $item['note']
        );
        $stmt_items->execute();

        // Update stock
        $stmt_stock->bind_param("ii", $item['quantity'], $item['id']);
        $stmt_stock->execute();
    }

    // Commit transaction
    $conn->commit();    // Redirect with JavaScript to clear sessionStorage and show receipt
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