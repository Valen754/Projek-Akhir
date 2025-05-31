<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$menu_id = intval($_POST['menu_id']);
$jumlah = intval($_POST['jumlah']);
$memo = $_POST['memo'] ?? '';

if ($menu_id <= 0 || $jumlah <= 0) {
    header("Location: ../menu/menu.php");
    exit();
}

// Ambil harga satuan
$stmt = $conn->prepare("SELECT price FROM menu WHERE id = ?");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$stmt->bind_result($harga_satuan);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: ../menu/menu.php");
    exit();
}
$stmt->close();

$total = $harga_satuan * $jumlah;
$customer_name = $_SESSION['nama'] ?? 'User';

// Simpan ke tabel orders dengan status 'Cancelled'
$stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, status, customer_name, payment_method, notes) VALUES (?, NOW(), ?, 'Cancelled', ?, '', ?)");
$stmt->bind_param("idss", $user_id, $total, $customer_name, $memo);
$stmt->execute();
$stmt->close();

// Tampilkan pesan dan kembali ke halaman sebelumnya
echo "<script>alert('Pesanan dibatalkan dan masuk riwayat dengan status Cancelled.'); window.history.back();</script>";
exit();