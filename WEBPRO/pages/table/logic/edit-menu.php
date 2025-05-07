<?php
include '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $url_foto = $_POST['url_foto'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    $query = "UPDATE menu SET
                nama='$nama',
                url_foto='$url_foto',
                type='$type',
                price='$price',
                quantity='$quantity',
                deskripsi='$deskripsi',
                status='$status'
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: table-menu.php?msg=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>