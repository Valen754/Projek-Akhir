<?php
// filepath: c:\xampp\htdocs\webpro\Projek-Akhir\WEBPRO\pages\keranjang\logic\update\u.keranjang_logic.php

// Include file koneksi database
include '../../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_keranjang = isset($_POST['id_keranjang']) ? intval($_POST['id_keranjang']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $memo = isset($_POST['memo']) ? trim($_POST['memo']) : '';

    // Validasi input
    if ($id_keranjang <= 0) {
        die('ID keranjang tidak valid.');
    }

    if ($quantity <= 0) {
        die('Jumlah barang harus lebih dari 0.');
    }

    // Update data keranjang di database
    $query = "UPDATE keranjang SET quantity = ?, memo = ? WHERE id_keranjang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isi', $quantity, $memo, $id_keranjang);

    if ($stmt->execute()) {
        echo 'Keranjang berhasil diperbarui.';
    } else {
        echo 'Terjadi kesalahan saat memperbarui keranjang.';
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
} else {
    die('Metode request tidak valid.');
}
?>