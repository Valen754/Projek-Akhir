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
    $status = $_POST['status'];
    
    try {
        // Begin transaction
        mysqli_begin_transaction($conn);
        
        // Update query - menghapus 'email' dan 'no_telp'
        $query = "UPDATE reservasi SET 
                  tanggal_reservasi = ?,
                  jumlah_orang = ?,
                  message = ?,
                  status = ?,
                  updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";
                  
        $stmt = mysqli_prepare($conn, $query);
        // Sesuaikan parameter bind_param
        mysqli_stmt_bind_param($stmt, "sissi", // s (tanggal_reservasi), i (jumlah_orang), s (message), s (status), i (id)
            $tanggal_reservasi,
            $jumlah_orang,
            $message,
            $status,
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

    mysqli_stmt_close($stmt);
} else {
    header("Location: ../treservasi.php");
    exit;
}
?>