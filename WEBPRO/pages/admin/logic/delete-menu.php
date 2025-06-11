<?php
include '../../../koneksi.php'; // Ubah sesuai dengan struktur direktori kamu

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    

    // Hapus data dari database
    $query = "DELETE FROM menu WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: ../menu.php?msg=deleted");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location:../menu.php");
}
?>
