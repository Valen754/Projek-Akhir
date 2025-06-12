<?php
include '../../koneksi.php';
session_start();

$reservation_id = $_GET['reservation_id'];
$user_id = $_SESSION['user_id'];

// Get reservation and cancellation details in one query
$query = "SELECT 
            r.id,
            rc.alasan_ditolak as reason,
            rc.ditolak_oleh,
            rc.cancelled_at
          FROM reservasi r 
          LEFT JOIN reservasi_ditolak rc ON r.id = rc.reservation_id
          WHERE r.id = ? 
          AND r.user_id = ? 
          AND r.status = 'dibatalkan'";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
