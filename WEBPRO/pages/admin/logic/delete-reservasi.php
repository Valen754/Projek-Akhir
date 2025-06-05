<?php
include '../../../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Begin transaction
        mysqli_begin_transaction($conn);
        
        // Delete query with prepared statement
        $query = "DELETE FROM reservasi WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            header("Location: ../treservasi.php?msg=deleted");
            exit;
        } else {
            throw new Exception("Error deleting reservation: " . mysqli_error($conn));
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: ../treservasi.php");
    exit;
}