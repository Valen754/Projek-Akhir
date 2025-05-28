<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;
    $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;
    $harga_satuan = isset($_POST['harga_satuan']) ? floatval($_POST['harga_satuan']) : 0;
    $memo = isset($_POST['memo']) ? trim($_POST['memo']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($menu_id <= 0 || $jumlah <= 0 || $harga_satuan <= 0) {
        // Data tidak valid
        $_SESSION['error_message'] = "Data pesanan tidak valid.";
        header("Location: ../../menu/menu.php"); // Arahkan kembali atau ke halaman error
        exit();
    }

    $total_harga_item = $harga_satuan * $jumlah;

    // Cek apakah item sudah ada di keranjang untuk user ini
    $stmt_check = $koneksi->prepare("SELECT id_keranjang, jumlah, memo FROM keranjang WHERE id_user = ? AND menu_id = ?");
    $stmt_check->bind_param("ii", $user_id, $menu_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $existing_item = $result_check->fetch_assoc();
    $stmt_check->close();

    if ($existing_item) {
        // Item sudah ada, update jumlah dan memo
        $id_keranjang = $existing_item['id_keranjang'];
        $jumlah_baru = $existing_item['jumlah'] + $jumlah; // Tambah jumlah yang baru
        $total_harga_baru = $harga_satuan * $jumlah_baru;
        
        // Gabungkan memo jika ada memo lama dan memo baru tidak kosong
        // Atau ganti dengan memo baru jika diinginkan. Di sini saya contohkan menggabungkan.
        $memo_final = $existing_item['memo'];
        if (!empty($memo)) {
            $memo_final = !empty($memo_final) ? $memo_final . " | " . $memo : $memo;
        }

        $stmt_update = $koneksi->prepare("UPDATE keranjang SET jumlah = ?, total_harga = ?, memo = ? WHERE id_keranjang = ?");
        $stmt_update->bind_param("idsi", $jumlah_baru, $total_harga_baru, $memo_final, $id_keranjang);
        if ($stmt_update->execute()) {
            $_SESSION['success_message'] = "Jumlah item di keranjang diperbarui.";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui item di keranjang.";
        }
        $stmt_update->close();
    } else {
        // Item belum ada, insert baru
        $stmt_insert = $koneksi->prepare("INSERT INTO keranjang (id_user, menu_id, jumlah, total_harga, memo) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("iiids", $user_id, $menu_id, $jumlah, $total_harga_item, $memo);
        if ($stmt_insert->execute()) {
            $_SESSION['success_message'] = "Item berhasil ditambahkan ke keranjang.";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan item ke keranjang: " . $stmt_insert->error;
        }
        $stmt_insert->close();
    }
    $koneksi->close();

    // Pengalihan berdasarkan aksi
    if ($action === 'tambah_keranjang') {
        header("Location: ../../menu/menu.php"); // Kembali ke menu setelah tambah
        exit();
    } elseif ($action === 'langsung_bayar') {
        header("Location: ../../keranjang/keranjang.php"); // Arahkan ke halaman keranjang untuk checkout
        exit();
    } else {
        // Aksi tidak dikenal, default redirect
        header("Location: ../../menu/menu.php");
        exit();
    }

} else {
    // Metode request bukan POST
    header("Location: ../../menu/menu.php");
    exit();
}
?>