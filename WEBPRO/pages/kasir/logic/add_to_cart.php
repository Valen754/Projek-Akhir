<?php
session_start();
include '../../../koneksi.php';

$menu_id = $_POST['menu_id'];
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['menu_id'] == $menu_id) {
        $item['quantity']++;
        $item['subtotal'] = $item['quantity'] * $item['price_per_item'];
        $found = true;
        break;
    }
}
if (!$found) {
    $q = mysqli_query($conn, "SELECT * FROM menu WHERE id = $menu_id");
    $data = mysqli_fetch_assoc($q);
    $_SESSION['cart'][] = [
        'menu_id' => $data['id'],
        'nama' => $data['nama'],
        'price_per_item' => $data['price'],
        'quantity' => 1,
        'subtotal' => $data['price'],
        'url_foto' => $data['url_foto'],
        'item_notes' => ''
    ];
}

echo json_encode($_SESSION['cart']);
