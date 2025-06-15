<?php

$user_id = $_SESSION['user_id'] ?? null;

if (isset($_POST['submit_reservation']) && $user_id) {
    // $name = $_POST['name']; // This data is from the form, but not inserted into 'reservasi' table
    // $phone = $_POST['phone']; // This data is from the form, but not inserted into 'reservasi' table
    // $email = $_POST['email']; // This data is from the form, but not inserted into 'reservasi' table
    $number_of_people = $_POST['number_of_people'];
    $date   = $_POST['date'];
    $hour   = $_POST['hour'];
    $message = $_POST['message'];

    // Gabungkan tanggal dan jam menjadi format datetime
    $tanggal_reservasi = "$date $hour:00";

    // Generate kode reservasi unik
    $kode_reservasi = 'RSV' . date('YmdHis') . rand(100, 999);

    try {
        // Get status_id for 'pending' from reservation_status table
        $status_name = 'pending';
        $query_status_id = "SELECT id FROM reservation_status WHERE status_name = ?";
        $stmt_status = $conn->prepare($query_status_id);
        if (!$stmt_status) { throw new Exception('Error preparing status lookup statement: ' . $conn->error); }
        $stmt_status->bind_param("s", $status_name);
        $stmt_status->execute();
        $result_status = $stmt_status->get_result();
        if ($result_status && $row_status = $result_status->fetch_assoc()) {
            $status_id = $row_status['id'];
        } else { throw new Exception("Status 'pending' not found in database."); }
        $stmt_status->close();

        // Query INSERT diubah: menggunakan status_id
        $stmt = $conn->prepare("
            INSERT INTO reservasi 
                (user_id, kode_reservasi, tanggal_reservasi, jumlah_orang, message, status_id, created_at, updated_at) 
            VALUES 
                (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        // bind_param diubah: i (user_id), s (kode_reservasi), s (tanggal_reservasi), i (jumlah_orang), s (message), i (status_id)
        $stmt->bind_param(
            "issisi", // i (user_id), s (kode_reservasi), s (tanggal_reservasi), i (jumlah_orang), s (message), i (status_id)
            $user_id,
            $kode_reservasi,
            $tanggal_reservasi,
            $number_of_people,
            $message,
            $status_id // Using status_id now
        );

        if ($stmt->execute()) {
            $success = "Reservasi berhasil dibuat! Kode reservasi Anda: <strong>$kode_reservasi</strong>";
            // Redirect to a success page or display success message
            header("Location: ../../profil/riwayat_reservasi.php?msg=success&kode=" . urlencode($kode_reservasi));
            exit();
        } else {
            $error = "Terjadi kesalahan saat menyimpan reservasi: " . $stmt->error; // Tambahkan detail error
            // Redirect to an error page or display error message
            header("Location: ../reservasi.php?msg=error&err=" . urlencode($error));
            exit();
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
        // Redirect to an error page or display error message
        header("Location: ../reservasi.php?msg=error&err=" . urlencode($error));
        exit();
    }
} elseif (!$user_id) {
    $error = "Anda harus login terlebih dahulu untuk melakukan reservasi.";
    header("Location: ../../login/login.php?error=" . urlencode($error));
    exit();
}
?>