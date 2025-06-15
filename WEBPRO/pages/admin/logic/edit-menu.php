<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $type_name  = $_POST['type'];   // Renamed to avoid conflict with type_id
    $price      = $_POST['price'];
    // $quantity   = $_POST['quantity']; // Dihapus karena kolom 'quantity' tidak ada di tabel 'menu'
    $deskripsi  = $_POST['deskripsi'];
    $status_name = $_POST['status']; // Renamed to avoid conflict with status_id
    $fotoLama   = $_POST['url_foto_lama']; // dari input hidden

    $url_foto = $fotoLama; // default gunakan foto lama

    // Jika user upload file baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoBaru = $_FILES['foto']['name'];
        $tmpName = $_FILES['foto']['tmp_name'];
        $targetDir = '../../../asset/';
        $pathFoto = $targetDir . basename($fotoBaru);

        // Pindahkan file
        if (move_uploaded_file($tmpName, $pathFoto)) {
            $url_foto = $fotoBaru;

            // Optional: hapus foto lama jika tidak ingin menumpuk file
            $fotoLamaPath = $targetDir . $fotoLama;
            if (file_exists($fotoLamaPath) && $fotoLama !== $fotoBaru) {
                unlink($fotoLamaPath);
            }
        }
    }

    // Get type_id from menu_types table
    $query_type_id = "SELECT id FROM menu_types WHERE type_name = '$type_name'";
    $result_type_id = mysqli_query($conn, $query_type_id);
    if ($result_type_id && mysqli_num_rows($result_type_id) > 0) {
        $row_type = mysqli_fetch_assoc($result_type_id);
        $type_id = $row_type['id'];
    } else {
        echo "Error: Menu type not found.";
        exit;
    }

    // Get status_id from menu_status table
    $query_status_id = "SELECT id FROM menu_status WHERE status_name = '$status_name'";
    $result_status_id = mysqli_query($conn, $query_status_id);
    if ($result_status_id && mysqli_num_rows($result_status_id) > 0) {
        $row_status = mysqli_fetch_assoc($result_status_id);
        $status_id = $row_status['id'];
    } else {
        echo "Error: Menu status not found.";
        exit;
    }

    // Query diubah untuk menggunakan type_id dan status_id
    $query = "UPDATE menu SET
                nama = '$nama',
                url_foto = '$url_foto',
                type_id = '$type_id',
                price = '$price',
                deskripsi = '$deskripsi',
                status_id = '$status_id'
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: ../menu.php?msg=updated");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>