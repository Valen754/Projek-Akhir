<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // Get the form data
        $order_id = $_POST['order_id'];
        $customer_name = $_POST['customer_name'];
        $payment_method = $_POST['payment_method'];
        $status = $_POST['status'];
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

        // Validate the data
        if (empty($order_id) || empty($customer_name) || empty($payment_method) || empty($status)) {
            throw new Exception("Semua field harus diisi");
        }

        // Prepare the SQL query to update the order
        $query = "UPDATE pembayaran SET 
                  customer_name = ?,
                  payment_method = ?,
                  status = ?,
                  notes = ?
                  WHERE id = ?";

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssssi", $customer_name, $payment_method, $status, $notes, $order_id);

        // Execute the query
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing statement: " . mysqli_stmt_error($stmt));
        }

        // Check if any rows were affected
        if (mysqli_stmt_affected_rows($stmt) === 0) {
            throw new Exception("Tidak ada perubahan data yang disimpan");
        }

        // Commit the transaction
        mysqli_commit($conn);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Redirect back to orders page with success message
        header("Location: ../torders.php?msg=updated");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($conn);
        
        // Log the error (in a production environment, you'd want to log this properly)
        error_log("Error updating order: " . $e->getMessage());
        
        // Redirect with error message
        header("Location: ../torders.php?msg=error&error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // If not a POST request, redirect to orders page
    header("Location: ../torders.php");
    exit;
}