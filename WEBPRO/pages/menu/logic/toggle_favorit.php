<?php
// Tambahkan di baris paling atas
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id'])) {
    $user_id = $_SESSION['user_id'];
    $menu_id = intval($_POST['menu_id']);

    // Cek apakah sudah favorit
    $check_query = "SELECT * FROM favorites WHERE user_id = ? AND menu_id = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("ii", $user_id, $menu_id);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();

    if ($check_result->num_rows > 0) {
        // Sudah favorit, hapus
        $delete_query = "DELETE FROM favorites WHERE user_id = ? AND menu_id = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("ii", $user_id, $menu_id);
        $stmt_delete->execute();
        $stmt_delete->close();
        $_SESSION['favorit_message'] = "Menu berhasil dihapus dari favorit!";
    } else {
        // Belum favorit, tambah
        $insert_query = "INSERT INTO favorites (user_id, menu_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($insert_query);
        $stmt_insert->bind_param("ii", $user_id, $menu_id);
        $stmt_insert->execute();
        $stmt_insert->close();
        $_SESSION['favorit_message'] = "Menu berhasil ditambahkan ke favorit!";
    }
    $stmt_check->close();
}

$conn->close();
header("Location: ../menu.php");
exit;
?>