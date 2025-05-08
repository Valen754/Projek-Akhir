<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak.");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$rating = $_POST['rating'];
$comment = trim($_POST['comment']);

if ($rating >= 1 && $rating <= 5 && $comment != '') {
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, menu_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
    $stmt->execute();
}

header("Location: ../detail.php?id=" . $product_id);
exit;
?>