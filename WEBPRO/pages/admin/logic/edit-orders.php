<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // Get the form data
        $order_id = $_POST['order_id'];
        $payment_method_name = $_POST['payment_method']; // Changed variable name to avoid conflict with 'payment_method_id'
        $status_name = $_POST['status']; // Changed variable name to avoid conflict with 'status_id'

        // Validate the data
        if (empty($order_id) || empty($payment_method_name) || empty($status_name)) {
            throw new Exception("Order ID, Metode Pembayaran, dan Status harus diisi");
        }

        // Get payment_method_id from payment_methods table
        $query_method_id = "SELECT id FROM payment_methods WHERE method_name = ?";
        $stmt_method = mysqli_prepare($conn, $query_method_id);
        if (!$stmt_method) {
            throw new Exception("Error preparing payment method statement: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_method, "s", $payment_method_name);
        mysqli_stmt_execute($stmt_method);
        $result_method = mysqli_stmt_get_result($stmt_method);
        if ($result_method && mysqli_num_rows($result_method) > 0) {
            $row_method = mysqli_fetch_assoc($result_method);
            $payment_method_id = $row_method['id'];
        } else {
            throw new Exception("Metode Pembayaran tidak ditemukan.");
        }
        mysqli_stmt_close($stmt_method);

        // Get status_id from payment_status table
        $query_status_id = "SELECT id FROM payment_status WHERE status_name = ?";
        $stmt_status = mysqli_prepare($conn, $query_status_id);
        if (!$stmt_status) {
            throw new Exception("Error preparing payment status statement: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_status, "s", $status_name);
        mysqli_stmt_execute($stmt_status);
        $result_status = mysqli_stmt_get_result($stmt_status);
        if ($result_status && mysqli_num_rows($result_status) > 0) {
            $row_status = mysqli_fetch_assoc($result_status);
            $status_id = $row_status['id'];
        } else {
            throw new Exception("Status pembayaran tidak ditemukan.");
        }
        mysqli_stmt_close($stmt_status);

        // Prepare the SQL query to update the order using foreign key IDs
        $query = "UPDATE pembayaran SET 
                      payment_method_id = ?,
                      status_id = ?
                  WHERE id = ?";

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception("Error preparing main update statement: " . mysqli_error($conn));
        }

        // Bind parameters: 'i' for integer IDs
        mysqli_stmt_bind_param($stmt, "iii", $payment_method_id, $status_id, $order_id);

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
?>