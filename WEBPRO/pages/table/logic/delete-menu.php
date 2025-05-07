<?php
include '../../../koneksi.php'; // Ubah sesuai dengan struktur direktori kamu

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data terlebih dahulu untuk hapus file foto jika perlu
    $result = mysqli_query($conn, "SELECT url_foto FROM menu WHERE id = $id");
    $data = mysqli_fetch_assoc($result);

    // Hapus file foto jika ada dan file eksis
    if ($data && !empty($data['url_foto']) && file_exists("../../asset/" . $data['url_foto'])) {
        unlink("../../../asset/" . $data['url_foto']);
    }

    // Hapus data dari database
    $query = "DELETE FROM menu WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: ../menu.php?msg=deleted");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: table-menu.php");
}
?>
