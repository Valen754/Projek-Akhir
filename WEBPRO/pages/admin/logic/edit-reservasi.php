<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $tanggal_reservasi = $_POST['tanggal_reservasi_date'] . ' ' . $_POST['tanggal_reservasi_time'];
    $jumlah_orang = $_POST['jumlah_orang'];
    // $email = $_POST['email']; // Dihapus karena kolom ini tidak ada di tabel `reservasi`
    // $no_telp = $_POST['no_telp']; // Dihapus karena kolom ini tidak ada di tabel `reservasi`
    $message = $_POST['message'];
    $status_name = $_POST['status']; // Changed variable name to avoid conflict with 'status_id'
    
    try {
        // Begin transaction
        mysqli_begin_transaction($conn);

        // Get status_id from reservation_status table
        $query_status_id = "SELECT id FROM reservation_status WHERE status_name = ?";
        $stmt_status = mysqli_prepare($conn, $query_status_id);
        if (!$stmt_status) {
            throw new Exception("Error preparing status statement: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_status, "s", $status_name);
        mysqli_stmt_execute($stmt_status);
        $result_status = mysqli_stmt_get_result($stmt_status);
        if ($result_status && mysqli_num_rows($result_status) > 0) {
            $row_status = mysqli_fetch_assoc($result_status);
            $status_id = $row_status['id'];
        } else {
            throw new Exception("Status reservasi tidak ditemukan.");
        }
        mysqli_stmt_close($stmt_status);
        
        // Update query - menghapus 'email' dan 'no_telp' dan menggunakan status_id
        $query = "UPDATE reservasi SET 
                      tanggal_reservasi = ?,
                      jumlah_orang = ?,
                      message = ?,
                      status_id = ?, -- Using status_id now
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";
                      
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception("Error preparing main update statement: " . mysqli_error($conn));
        }

        // Sesuaikan parameter bind_param: s (tanggal_reservasi), i (jumlah_orang), s (message), i (status_id), i (id)
        mysqli_stmt_bind_param($stmt, "sisii",
            $tanggal_reservasi,
            $jumlah_orang,
            $message,
            $status_id, // Now using ID
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            header("Location: ../treservasi.php?msg=updated");
            exit;
        } else {
            throw new Exception("Error updating reservation: " . mysqli_error($conn));
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }

    if (isset($stmt)) { // Only close if it was successfully prepared
        mysqli_stmt_close($stmt);
    }
} else {
    header("Location: ../treservasi.php");
    exit;
}
?>