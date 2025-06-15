<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $type_name = $_POST['type']; // Changed variable name to avoid conflict with 'type_id'
    $price = $_POST['price'];
    // $quantity = $_POST['quantity']; // Kolom ini dihapus karena tidak ada di tabel `menu`
    $deskripsi = $_POST['deskripsi'];
    $status_name = $_POST['status']; // Changed variable name to avoid conflict with 'status_id'

    // Direktori tujuan upload (relatif dari file add-menu.php)
    $upload_dir = '../../../asset/';
    $foto_name = basename($_FILES['url_foto']['name']);
    $target_path = $upload_dir . $foto_name;

    // Simpan path untuk database
    $db_path = $foto_name;

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

    if (move_uploaded_file($_FILES['url_foto']['tmp_name'], $target_path)) {
        // Query diubah untuk menggunakan type_id dan status_id, dan tidak menyertakan `quantity`
        $query = "INSERT INTO menu (nama, url_foto, type_id, price, deskripsi, status_id) 
                  VALUES ('$nama', '$db_path', '$type_id', '$price', '$deskripsi', '$status_id')";

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