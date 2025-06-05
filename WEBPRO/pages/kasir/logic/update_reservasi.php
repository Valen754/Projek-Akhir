<?php
include '../../../koneksi.php';
session_start();

header('Content-Type: application/json');

// Debug: Log semua data yang diterima
error_log('POST data received: ' . print_r($_POST, true));

// Pastikan user adalah kasir
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Cek method dan data yang diperlukan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    if (!isset($_POST['id']) || !isset($_POST['action'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing parameters: ' .
                'id=' . (isset($_POST['id']) ? $_POST['id'] : 'missing') .
                ', action=' . (isset($_POST['action']) ? $_POST['action'] : 'missing')
        ]);
        exit();
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid reservation ID']);
        exit();
    }

    $action = $_POST['action'];

    // Set status berdasarkan action
    if ($action === 'confirm') {
        $status = 'dikonfirmasi';
    } elseif ($action === 'cancel') {
        $status = 'dibatalkan';
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action: ' . $action]);
        exit();
    }

    try {
        // Update status reservasi
        $stmt = $conn->prepare("UPDATE reservasi SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND status = 'pending'");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        $stmt->bind_param("si", $status, $id);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("No pending reservation found with ID: " . $id);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Status reservasi berhasil diperbarui!',
            'data' => [
                'id' => $id,
                'new_status' => $status
            ]
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method: ' . $_SERVER['REQUEST_METHOD']
    ]);
}