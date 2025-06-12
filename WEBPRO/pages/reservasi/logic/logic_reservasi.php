<?php
include '../../koneksi.php'; // Pastikan path ini sesuai

$user_id = $_SESSION['user_id'] ?? null;

if (isset($_POST['submit_reservation']) && $user_id) {
    $name   = $_POST['name'];
    $phone  = $_POST['phone']; // Data ini dari form, tapi tidak akan dimasukkan ke tabel reservasi
    $email  = $_POST['email']; // Data ini dari form, tapi tidak akan dimasukkan ke tabel reservasi
    $number_of_people = $_POST['number_of_people'];
    $date   = $_POST['date'];
    $hour   = $_POST['hour'];
    $message = $_POST['message'];

    // Gabungkan tanggal dan jam menjadi format datetime
    $tanggal_reservasi = "$date $hour:00";

    // Generate kode reservasi unik
    $kode_reservasi = 'RSV' . date('YmdHis') . rand(100, 999);

    try {
        // Query INSERT diubah: menghapus kolom email dan no_telp
        $stmt = $conn->prepare("
            INSERT INTO reservasi 
                (user_id, kode_reservasi, tanggal_reservasi, jumlah_orang, message, status, created_at, updated_at) 
            VALUES 
                (?, ?, ?, ?, ?, 'pending', NOW(), NOW())
        ");

        // bind_param diubah: menghapus parameter email dan phone
        $stmt->bind_param(
            "issis", // i (user_id), s (kode_reservasi), s (tanggal_reservasi), i (jumlah_orang), s (message)
            $user_id,
            $kode_reservasi,
            $tanggal_reservasi,
            $number_of_people,
            $message
        );

        if ($stmt->execute()) {
            $success = "Reservasi berhasil dibuat! Kode reservasi Anda: <strong>$kode_reservasi</strong>";
        } else {
            $error = "Terjadi kesalahan saat menyimpan reservasi: " . $stmt->error; // Tambahkan detail error
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
} elseif (!$user_id) {
    $error = "Anda harus login terlebih dahulu untuk melakukan reservasi.";
}
?>