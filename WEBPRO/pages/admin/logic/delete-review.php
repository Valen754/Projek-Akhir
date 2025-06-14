<?php
session_start(); // Pastikan sesi sudah dimulai
include '../../../koneksi.php'; 

if (isset($_GET['id'])) {
    $id_review = $_GET['id'];

    // Siapkan statement SQL untuk menghapus review berdasarkan id
    $sql = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_review); // "i" karena id_review kemungkinan integer

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Review berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Error saat menghapus review: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect kembali ke halaman tabel review setelah proses selesai
    header("Location: ../../admin/treviews.php"); // Sesuaikan dengan halaman tabel review Anda
    exit();
} else {
    // Jika id_review tidak ditemukan di URL
    $_SESSION['error_message'] = "ID Review tidak ditemukan.";
    header("Location: ../../index.php?page=treviews"); // Redirect kembali ke halaman tabel review
    exit();
}
?>