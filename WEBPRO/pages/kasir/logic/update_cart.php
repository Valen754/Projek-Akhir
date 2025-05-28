<?php
session_start();
$menu_id = $_POST['menu_id'];

foreach ($_SESSION['cart'] as &$item) {
    if ($item['menu_id'] == $menu_id) {
        if (isset($_POST['delta'])) {
            $item['quantity'] += (int)$_POST['delta'];
            if ($item['quantity'] < 1) $item['quantity'] = 1;
            $item['subtotal'] = $item['quantity'] * $item['price_per_item'];
        }
        if (isset($_POST['note'])) {
            $item['item_notes'] = $_POST['note'];
        }
        break;
    }
}
echo json_encode($_SESSION['cart']);
