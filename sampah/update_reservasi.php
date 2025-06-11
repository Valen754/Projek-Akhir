<?php
include '../../koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'confirm') {
        $status = 'dikonfirmasi';
    } elseif ($action === 'cancel') {
        $status = 'dibatalkan';
    }

    $stmt = $conn->prepare("UPDATE reservasi SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: notif.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui status reservasi.";
    }
}
?>