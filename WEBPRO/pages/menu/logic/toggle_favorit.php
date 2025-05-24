<?php
session_start();
include '../../koneksi.php'; // Pastikan path benar

header('Content-Type: application/json'); // Mengirimkan respons dalam format JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id'])) {
    $user_id = $_SESSION['user_id'];
    $menu_id = mysqli_real_escape_string($conn, $_POST['menu_id']);

    // Periksa apakah menu sudah difavoritkan
    $check_query = "SELECT * FROM favorites WHERE user_id = '$user_id' AND menu_id = '$menu_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Sudah favorit, hapus dari favorit
        $delete_query = "DELETE FROM favorites WHERE user_id = '$user_id' AND menu_id = '$menu_id'";
        if (mysqli_query($conn, $delete_query)) {
            echo json_encode(['status' => 'success', 'action' => 'removed', 'message' => 'Menu removed from favorites.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove from favorites: ' . mysqli_error($conn)]);
        }
    } else {
        // Belum favorit, tambahkan ke favorit
        $insert_query = "INSERT INTO favorites (user_id, menu_id) VALUES ('$user_id', '$menu_id')";
        if (mysqli_query($conn, $insert_query)) {
            echo json_encode(['status' => 'success', 'action' => 'added', 'message' => 'Menu added to favorites.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to favorites: ' . mysqli_error($conn)]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

mysqli_close($conn);
?>