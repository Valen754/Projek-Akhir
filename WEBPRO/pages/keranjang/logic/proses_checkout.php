<?php
include '../../../koneksi.php'; // Sesuaikan path koneksi Anda
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_ids_keranjang = $_POST['checkout_items'] ?? []; // Ini adalah order_id dari tabel keranjang
$payment_method = $_POST['payment_method'] ?? 'cash'; // Tambahkan input ini dari form checkout

if (empty($order_ids_keranjang)) {
    echo "Tidak ada item yang dipilih untuk checkout.";
    exit();
}

$conn->begin_transaction(); // Mulai transaksi

try {
    // 1. Hitung total harga dan siapkan detail item
    $total_amount_order = 0;
    $items_to_process = [];

    $order_ids_str = implode(",", array_map('intval', $order_ids_keranjang));
    // Ambil data keranjang, termasuk harga per unit dari tabel menu
    $sql_keranjang = "SELECT 
                        k.order_id, 
                        k.menu_id, 
                        k.quantity, 
                        m.price AS unit_price,
                        k.catatan AS item_notes
                      FROM 
                        keranjang k
                      JOIN 
                        menu m ON k.menu_id = m.id
                      WHERE 
                        k.order_id IN ($order_ids_str) AND k.user_id = ?";
    
    $stmt_keranjang = $conn->prepare($sql_keranjang);
    $stmt_keranjang->bind_param("i", $user_id);
    $stmt_keranjang->execute();
    $result_keranjang = $stmt_keranjang->get_result();

    if ($result_keranjang->num_rows == 0) {
        throw new Exception("Data keranjang tidak ditemukan atau sudah kosong.");
    }

    while ($row_keranjang = $result_keranjang->fetch_assoc()) {
        $menu_id = $row_keranjang['menu_id'];
        $quantity = $row_keranjang['quantity'];
        $unit_price = $row_keranjang['unit_price'];
        $subtotal_item = $quantity * $unit_price;

        $total_amount_order += $subtotal_item;

        $items_to_process[] = [
            'keranjang_order_id' => $row_keranjang['order_id'],
            'menu_id' => $menu_id,
            'quantity' => $quantity,
            'price_per_item' => $unit_price,
            'subtotal' => $subtotal_item,
            'item_notes' => $row_keranjang['item_notes']
        ];
    }
    $stmt_keranjang->close();

    // 2. Masukkan ke tabel 'orders'
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, customer_name, payment_method, notes) VALUES (?, ?, ?, ?, ?)");
    // Anda perlu menentukan customer_name dan notes di sini. Untuk kasir, mungkin ada input tambahan.
    // Untuk saat ini, kita bisa menggunakan username sebagai customer_name, dan notes kosong.
    $customer_name = $_SESSION['username'] ?? 'Guest'; // Atau ambil dari input form
    $order_notes = ''; // Ambil dari input form jika ada

    $stmt_order->bind_param("idsss", $user_id, $total_amount_order, $customer_name, $payment_method, $order_notes);
    $stmt_order->execute();
    $order_id_new = $conn->insert_id; // Dapatkan ID pesanan yang baru dibuat
    $stmt_order->close();

    // 3. Masukkan detail ke tabel 'order_details'
    $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, menu_id, quantity, price_per_item, subtotal, item_notes) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($items_to_process as $item) {
        $stmt_detail->bind_param(
            "iiidss",
            $order_id_new,
            $item['menu_id'],
            $item['quantity'],
            $item['price_per_item'],
            $item['subtotal'],
            $item['item_notes']
        );
        $stmt_detail->execute();

        // 4. Update stok menu
        $stmt_update_stock = $conn->prepare("UPDATE menu SET quantity = quantity - ? WHERE id = ?");
        $stmt_update_stock->bind_param("ii", $item['quantity'], $item['menu_id']);
        $stmt_update_stock->execute();
        $stmt_update_stock->close();
    }
    $stmt_detail->close();

    // 5. Hapus item dari keranjang
    $stmt_delete_keranjang = $conn->prepare("DELETE FROM keranjang WHERE order_id IN ($order_ids_str) AND user_id = ?");
    $stmt_delete_keranjang->bind_param("i", $user_id);
    $stmt_delete_keranjang->execute();
    $stmt_delete_keranjang->close();

    $conn->commit(); // Commit transaksi jika semua berhasil
    header("Location: ../checkout_success.php");
    exit();

} catch (Exception $e) {
    $conn->rollback(); // Rollback jika ada kesalahan
    echo "Gagal melakukan checkout: " . $e->getMessage();
    // Anda bisa mengarahkan kembali ke keranjang dengan pesan error
    // header("Location: ../keranjang.php?status=error&msg=" . urlencode($e->getMessage()));
    exit();
}

$conn->close();
?>