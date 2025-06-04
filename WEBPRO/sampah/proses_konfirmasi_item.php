<?php
session_start();
include '../../../koneksi.php';

$user_id = $_SESSION['user_id'];
$menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;
$quantity = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 1; // Ambil dari input 'jumlah'
$catatan = isset($_POST['memo']) ? trim($_POST['memo']) : ''; // Ambil dari input 'memo'

// Ambil harga satuan dari menu
$result = mysqli_query($conn, "SELECT price FROM menu WHERE id = $menu_id");
$row = mysqli_fetch_assoc($result);
$price = $row ? ($row['price'] * $quantity) : 0;

// Insert ke keranjang
$query = "INSERT INTO keranjang (user_id, menu_id, quantity, price, catatan) VALUES ($user_id, $menu_id, $quantity, $price, '" . $conn->real_escape_string($catatan) . "')";
if (mysqli_query($conn, $query)) {
    header('Location: ../../keranjang/keranjang.php?msg=added');
    exit();
} else {
    die('Gagal menambah ke keranjang: ' . mysqli_error($conn));
}
?>
