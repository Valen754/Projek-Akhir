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
$role_name = $_POST['role'] ?? ''; // Renamed to avoid conflict with role_id

try {
    switch ($action) {
        case 'update':
            if (empty($userId) || empty($nama) || empty($email) || empty($role_name)) {
                throw new Exception('All fields are required');
            }

            // Get role_id from user_roles table
            $query_role_id = "SELECT id FROM user_roles WHERE role_name = ?";
            $stmt_role = $conn->prepare($query_role_id);
            if (!$stmt_role) {
                throw new Exception("Error preparing role statement: " . mysqli_error($conn));
            }
            $stmt_role->bind_param("s", $role_name);
            $stmt_role->execute();
            $result_role = $stmt_role->get_result();
            if ($result_role && $row_role = $result_role->fetch_assoc()) {
                $role_id = $row_role['id'];
            } else {
                throw new Exception("User role not found.");
            }
            $stmt_role->close();

            // Update query using role_id
            $sql = "UPDATE users SET nama = ?, email = ?, role_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing update statement: " . mysqli_error($conn));
            }
            $stmt->bind_param("ssii", $nama, $email, $role_id, $userId); // 'i' for role_id

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
            if (!$stmt) {
                throw new Exception("Error preparing delete statement: " . mysqli_error($conn));
            }
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

            // Select role_name by joining with user_roles table
            $sql = "SELECT u.id, u.nama, u.email, ur.role_name AS role FROM users u JOIN user_roles ur ON u.role_id = ur.id WHERE u.id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing get statement: " . mysqli_error($conn));
            }
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
    // Close the statement if it was initialized
    if (isset($stmt) && is_object($stmt)) {
        $stmt->close();
    }
    // Close the connection
    if (isset($conn) && is_object($conn)) {
        $conn->close();
    }
}
?>