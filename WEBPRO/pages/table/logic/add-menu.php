<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    // Direktori tujuan upload (relatif dari file add-menu.php)
    $upload_dir = '../../../asset/';
    $foto_name = basename($_FILES['url_foto']['name']);
    $target_path = $upload_dir . $foto_name;

    // Simpan path untuk database
    $db_path = $foto_name;

    if (move_uploaded_file($_FILES['url_foto']['tmp_name'], $target_path)) {
        $query = "INSERT INTO menu (nama, url_foto, type, price, quantity, deskripsi, status) 
                  VALUES ('$nama', '$db_path', '$type', '$price', '$quantity', '$deskripsi', '$status')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../menu.php?msg=added");
            exit;
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal upload file.";
    }
}
?>
