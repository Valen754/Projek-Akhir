<?php
include '../../../koneksi.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First, delete from detail_pembayaran
        $detail_query = "DELETE FROM detail_pembayaran WHERE order_id = ?";
        $detail_stmt = mysqli_prepare($conn, $detail_query);
        mysqli_stmt_bind_param($detail_stmt, "i", $order_id);
        mysqli_stmt_execute($detail_stmt);
        mysqli_stmt_close($detail_stmt);

        // Then, delete from pembayaran
        $order_query = "DELETE FROM pembayaran WHERE id = ?";
        $order_stmt = mysqli_prepare($conn, $order_query);
        mysqli_stmt_bind_param($order_stmt, "i", $order_id);
        mysqli_stmt_execute($order_stmt);
        mysqli_stmt_close($order_stmt);

        // If both operations successful, commit transaction
        mysqli_commit($conn);
        header("Location: ../torders.php?msg=deleted");
        exit;

    } catch (Exception $e) {
        // If there's an error, rollback changes
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
} else {
    // If no ID provided, redirect back to orders page
    header("Location: ../torders.php");
    exit;
}