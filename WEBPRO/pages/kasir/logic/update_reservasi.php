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
    
    if ($action === 'cancel') {
        $reason = $_POST['reason'];
        
        $conn->begin_transaction();
        
        try {
            // 1. Update status di tabel reservasi
            $stmt = $conn->prepare("UPDATE reservasi SET status = 'dibatalkan', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate status reservasi");
            }
            
            // 2. Insert data ke tabel reservati_ditolak
            $stmt = $conn->prepare("INSERT INTO reservasi_ditolak (reservation_id, alasan_ditolak, ditolak_oleh) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id, $reason, $_SESSION['username']);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal menyimpan alasan pembatalan");
            }
            
            // Jika semua berhasil, commit transaksi
            $conn->commit();
            header("Location: ../notif.php?status=success&message=" . urlencode("Reservasi berhasil dibatalkan"));
            
        } catch (Exception $e) {
            // Jika terjadi error, rollback semua perubahan
            $conn->rollback();
            header("Location: ../notif.php?status=error&message=" . urlencode("Gagal membatalkan reservasi: " . $e->getMessage()));
        }
    } elseif ($action === 'confirm') {
        try {
            $stmt = $conn->prepare("UPDATE reservasi SET status = 'dikonfirmasi', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate status reservasi");
            }
            
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
