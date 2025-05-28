<?php
session_start();
$menu_id = $_POST['menu_id'];
$_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($menu_id) {
    return $item['menu_id'] != $menu_id;
});
echo json_encode(array_values($_SESSION['cart']));
