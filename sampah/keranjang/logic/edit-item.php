<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $quantity = $_POST['quantity'];
    $catatan = $_POST['catatan'];

    $query = "UPDATE keranjang SET quantity = ?, catatan = ? WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isi', $quantity, $catatan, $order_id);

    if ($stmt->execute()) {
        header("Location: ../keranjang.php?msg=updated");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>