<?php

header('Content-Type: application/json');
include '../../../koneksi.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$action = $_POST['action'] ?? '';
$userId = $_POST['id'] ?? '';
$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';

try {
    switch ($action) {
        case 'update':
            if (empty($userId) || empty($nama) || empty($email) || empty($role)) {
                throw new Exception('All fields are required');
            }

            $sql = "UPDATE users SET nama = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nama, $email, $role, $userId);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to update user');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
            break;

        case 'delete':
            if (empty($userId)) {
                throw new Exception('User ID is required');
            }

            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete user');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
            break;

        case 'get':
            if (empty($userId)) {
                throw new Exception('User ID is required');
            }

            $sql = "SELECT id, nama, email, role FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to fetch user data');
            }
            
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if (!$user) {
                throw new Exception('User not found');
            }
            
            echo json_encode([
                'success' => true,
                'data' => $user
            ]);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>