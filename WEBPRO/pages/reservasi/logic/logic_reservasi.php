<?php
include '../../koneksi.php'; // Pastikan path ini sesuai

$user_id = $_SESSION['user_id'] ?? null;

if (isset($_POST['submit_reservation']) && $user_id) {
    $name   = $_POST['name'];
    $phone  = $_POST['phone'];
    $email  = $_POST['email'];
    $number_of_people = $_POST['number_of_people'];
    $date   = $_POST['date'];
    $hour   = $_POST['hour'];
    $message = $_POST['message'];

    // Gabungkan tanggal dan jam menjadi format datetime
    $tanggal_reservasi = "$date $hour:00";

    // Generate kode reservasi unik
    $kode_reservasi = 'RSV' . date('YmdHis') . rand(100, 999);

    try {
        $stmt = $conn->prepare("
            INSERT INTO reservasi 
                (user_id, kode_reservasi, tanggal_reservasi, jumlah_orang, email, no_telp, message, status, created_at, updated_at) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
        ");

        $stmt->bind_param(
            "ississs",
            $user_id,
            $kode_reservasi,
            $tanggal_reservasi,
            $number_of_people,
            $email,
            $phone,
            $message
        );

        if ($stmt->execute()) {
            $success = "Reservasi berhasil dibuat! Kode reservasi Anda: <strong>$kode_reservasi</strong>";
        } else {
            $error = "Terjadi kesalahan saat menyimpan reservasi.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
} elseif (!$user_id) {
    $error = "Anda harus login terlebih dahulu untuk melakukan reservasi.";
}
?>
