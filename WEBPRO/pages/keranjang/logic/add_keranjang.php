<?php
session_start();
include '../../../koneksi.php';

$user_id = $_SESSION['user_id'];
$menu_id = $_POST['menu_id'];
$quantity = $_POST['quantity'];
$catatan = $_POST['catatan'];

// Ambil harga dari tabel menu
$result = mysqli_query($conn, "SELECT price FROM menu WHERE id = $menu_id");
$row = mysqli_fetch_assoc($result);
$price = $row['price'] * $quantity;

// Masukkan ke keranjang
mysqli_query($conn, "INSERT INTO keranjang (user_id, menu_id, quantity, price, catatan) VALUES ($user_id, $menu_id, $quantity, $price, '$catatan')");

header('Location: keranjang.php');
?>
