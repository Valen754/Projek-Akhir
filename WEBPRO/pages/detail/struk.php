<?php
include '../../views/header.php';
include '../../koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: menu.php');
    exit;
}

$order_id = (int) $_GET['id'];

// Get order details
// Query SQL disesuaikan untuk mengambil kolom yang sesuai dengan skema database
$sql = "SELECT p.id, p.user_id, p.order_date,
               ps.status_name AS order_status_name,          -- Get status name from payment_status
               pm.method_name AS payment_method_name,         -- Get method name from payment_methods
               ot.type_name AS order_type_name,               -- Get type name from order_types
               u.nama AS customer_name, 
               dp.menu_id, dp.quantity, dp.price_per_item AS price, dp.item_notes AS notes, 
               m.nama AS menu_name 
        FROM pembayaran p 
        JOIN users u ON p.user_id = u.id 
        JOIN detail_pembayaran dp ON p.id = dp.pembayaran_id 
        JOIN menu m ON dp.menu_id = m.id 
        JOIN payment_status ps ON p.status_id = ps.id        -- Join to get status name
        JOIN payment_methods pm ON p.payment_method_id = pm.id -- Join to get method name
        JOIN order_types ot ON p.order_type_id = ot.id       -- Join to get order type name
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: menu.php');
    exit;
}

$items = [];
$order = null;
$calculated_subtotal = 0;

while ($row = $result->fetch_assoc()) {
    if (!$order) {
        // Inisialisasi data order hanya sekali dari baris pertama
        $order = [
            'id' => $row['id'],
            'customer_name' => $row['customer_name'],
            'order_type_name' => $row['order_type_name'],     // Use fetched type name
            'payment_method_name' => $row['payment_method_name'], // Use fetched method name
            'order_status_name' => $row['order_status_name'], // Use fetched status name
            'order_date' => $row['order_date']
        ];
    }

    $item_total = $row['price'] * $row['quantity'];
    $items[] = [
        'name' => $row['menu_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'notes' => $row['notes'],
        'total' => $item_total
    ];
    $calculated_subtotal += $item_total;
}

$tax = round($calculated_subtotal * 0.10);
$total = $calculated_subtotal + $tax;

$order['subtotal'] = $calculated_subtotal;
$order['tax'] = $tax;
$order['total'] = $total;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - Tapal Kuda</title>
    <style>
        .struk-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 32px;
            background: #222b3a;
            border-radius: 12px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .struk-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .struk-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .struk-totals {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }

        .struk-info {
            margin: 20px 0;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .btn-print {
            width: 100%;
            background: #e07b6c;
            padding: 14px 0;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            background: #d45a4c;
        }

        .btn-kembali {
            width: 100%;
            margin-top: 12px;
            padding: 12px 0;
            border: 1px solid rgba(224, 123, 108, 0.3);
            border-radius: 10px;
            background: transparent;
            color: #e07b6c;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: block;
        }

        .btn-kembali:hover {
            background: rgba(224, 123, 108, 0.1);
        }

        @media print {
            .no-print {
                display: none;
            }

            .struk-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
                color: #000;
                background: #fff;
            }

            .struk-item,
            .struk-totals {
                border-color: #000;
            }
        }
    </style>
</head>

<body>
    <div class="struk-container">
        <div class="struk-header">
            <h2>Tapal Kuda</h2>
            <p><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
            <p>Order #<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></p>
        </div>

        <div class="struk-info">
            <div>Customer: <?php echo htmlspecialchars($order['customer_name']); ?></div>
            <div>Jenis: <?php echo htmlspecialchars($order['order_type_name']); ?></div> <div>Pembayaran: <?php echo htmlspecialchars($order['payment_method_name']); ?></div> </div>

        <?php foreach ($items as $item): ?>
            <div class="struk-item">
                <div>
                    <?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?>
                    <?php if ($item['notes']): ?>
                        <br><small style="color: #aaa;">Note: <?php echo htmlspecialchars($item['notes']); ?></small>
                    <?php endif; ?>
                </div>
                <div>Rp <?php echo number_format($item['total'], 0, ',', '.'); ?></div>
            </div>
        <?php endforeach; ?>

        <div class="struk-totals">
            <div class="struk-item">
                <div>Subtotal</div>
                <div>Rp <?php echo number_format($order['subtotal'], 0, ',', '.'); ?></div>
            </div>
            <div class="struk-item">
                <div>Pajak (10%)</div>
                <div>Rp <?php echo number_format($order['tax'], 0, ',', '.'); ?></div>
            </div>
            <div class="struk-item" style="border-bottom: none;">
                <div><strong>Total</strong></div>
                <div><strong>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></strong></div>
            </div>
        </div>

        <button onclick="window.print()" class="btn-print no-print">Cetak Struk</button>
        <a href="../../pages/menu/menu.php" class="btn-kembali no-print">Kembali ke Menu</a>
    </div>
</body>

</html>