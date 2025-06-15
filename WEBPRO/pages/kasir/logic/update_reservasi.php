<?php
include '../../../koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];
    
    // Fetch status IDs from reservation_status table once
    $cancelled_status_id = null;
    $confirmed_status_id = null;
    
    $stmt_status_lookup = $conn->prepare("SELECT id, status_name FROM reservation_status WHERE status_name IN (?, ?)");
    if ($stmt_status_lookup) {
        $status_name_cancelled = 'dibatalkan';
        $status_name_confirmed = 'dikonfirmasi';
        $stmt_status_lookup->bind_param("ss", $status_name_cancelled, $status_name_confirmed);
        $stmt_status_lookup->execute();
        $result_status_lookup = $stmt_status_lookup->get_result();
        while ($row_status = $result_status_lookup->fetch_assoc()) {
            if ($row_status['status_name'] === 'dibatalkan') {
                $cancelled_status_id = $row_status['id'];
            } elseif ($row_status['status_name'] === 'dikonfirmasi') {
                $confirmed_status_id = $row_status['id'];
            }
        }
        $stmt_status_lookup->close();
    } else {
        header("Location: ../notif.php?status=error&message=" . urlencode("Database error: Could not prepare status lookup."));
        exit();
    }

    if ($action === 'cancel') {
        $reason = $_POST['reason'];
        
        if ($cancelled_status_id === null) {
            header("Location: ../notif.php?status=error&message=" . urlencode("Status 'dibatalkan' not found in database."));
            exit();
        }

        $conn->begin_transaction();
        
        try {
            // 1. Update status di tabel reservasi menggunakan status_id
            $stmt = $conn->prepare("UPDATE reservasi SET status_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            if (!$stmt) { throw new Exception("Failed to prepare update reservation status statement: " . $conn->error); }
            $stmt->bind_param("ii", $cancelled_status_id, $id); // Use status_id
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate status reservasi: " . $stmt->error);
            }
            $stmt->close();
            
            // 2. Insert data ke tabel reservasi_ditolak
            $stmt = $conn->prepare("INSERT INTO reservasi_ditolak (reservation_id, alasan_ditolak, ditolak_oleh) VALUES (?, ?, ?)");
            if (!$stmt) { throw new Exception("Failed to prepare insert cancelled reservation statement: " . $conn->error); }
            $stmt->bind_param("iss", $id, $reason, $_SESSION['username']);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal menyimpan alasan pembatalan: " . $stmt->error);
            }
            $stmt->close();
            
            // Jika semua berhasil, commit transaksi
            $conn->commit();
            header("Location: ../notif.php?status=success&message=" . urlencode("Reservasi berhasil dibatalkan"));
            
        } catch (Exception $e) {
            // Jika terjadi error, rollback semua perubahan
            $conn->rollback();
            header("Location: ../notif.php?status=error&message=" . urlencode("Gagal membatalkan reservasi: " . $e->getMessage()));
        }
    } elseif ($action === 'confirm') {
        if ($confirmed_status_id === null) {
            header("Location: ../notif.php?status=error&message=" . urlencode("Status 'dikonfirmasi' not found in database."));
            exit();
        }
        try {
            // Update status di tabel reservasi menggunakan status_id
            $stmt = $conn->prepare("UPDATE reservasi SET status_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            if (!$stmt) { throw new Exception("Failed to prepare update reservation status statement: " . $conn->error); }
            $stmt->bind_param("ii", $confirmed_status_id, $id); // Use status_id
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate status reservasi: " . $stmt->error);
            }
            $stmt->close();
            
            header("Location: ../notif.php?status=success&message=" . urlencode("Reservasi berhasil dikonfirmasi"));
        } catch (Exception $e) {
            header("Location: ../notif.php?status=error&message=" . urlencode("Gagal mengkonfirmasi reservasi: " . $e->getMessage()));
        }
    }
    exit();
} else {
    header("Location: ../notif.php");
    exit();
}