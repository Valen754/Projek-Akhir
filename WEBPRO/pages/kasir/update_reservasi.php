<?php
include '../../koneksi.php';
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'])) {

    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'confirm') {
        $status = 'dikonfirmasi';
    } elseif ($action === 'cancel') {
        $status = 'dibatalkan';
    } else {
        header("Location: notif.php?error=invalid_action");
        exit();
    }


    $stmt = $conn->prepare("UPDATE reservasi SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $conn->commit();
        echo "Update berhasil";
        // Jika mau redirect, gunakan ini, tapi echo dulu untuk tes:
        header("Location: notif.php?success=1");
    } else {
        echo "Update gagal: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: notif.php?error=invalid_request");
    exit();
}