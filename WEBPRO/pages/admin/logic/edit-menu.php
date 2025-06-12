<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $type       = $_POST['type'];
    $price      = $_POST['price'];
    // $quantity   = $_POST['quantity']; // Dihapus karena kolom 'quantity' tidak ada di tabel 'menu'
    $deskripsi  = $_POST['deskripsi'];
    $status     = $_POST['status'];
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

    $query = "UPDATE menu SET
                nama = '$nama',
                url_foto = '$url_foto',
                type = '$type',
                price = '$price',
                deskripsi = '$deskripsi',
                status = '$status'
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: ../menu.php?msg=updated");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>