<?php
session_start();
include '../../../koneksi.php'; // Pastikan jalur ini benar

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

// Tambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : '';
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session
    
    // Periksa apakah item sudah ada di keranjang untuk user ini
    $checkQuery = "SELECT * FROM keranjang WHERE menu_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $menu_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika item sudah ada, tambahkan jumlahnya
        $updateQuery = "UPDATE keranjang SET quantity = quantity + ?, catatan = ? WHERE menu_id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("isii", $quantity, $catatan, $menu_id, $user_id);
        $stmt->execute();
    } else {
        // Jika item belum ada, tambahkan ke keranjang
        $insertQuery = "INSERT INTO keranjang (menu_id, user_id, catatan, quantity, price) 
                        SELECT ?, ?, ?, ?, price FROM menu WHERE id = ?";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iisii", $menu_id, $user_id, $catatan, $quantity, $menu_id);
        $stmt->execute();
    }

    // Redirect kembali ke halaman keranjang
    header("Location: ../../keranjang/keranjang.php");
    exit();
}
?>