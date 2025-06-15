<?php
include '../../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk mendapatkan data pengguna berdasarkan username, JOIN dengan user_roles untuk mendapatkan role_name
    $sql = "SELECT u.*, ur.role_name 
            FROM users u 
            JOIN user_roles ur ON u.role_id = ur.id 
            WHERE u.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataUser = $result->fetch_assoc();

    if ($dataUser) {
        // Verifikasi password menggunakan md5()
        if (md5($password) === $dataUser['password']) {
            // Login berhasil
            session_start();
            $_SESSION['user_id'] = $dataUser['id']; // Simpan user_id ke session
            $_SESSION['username'] = $dataUser['username'];
            $_SESSION['role'] = $dataUser['role_name']; // Simpan role_name ke session

            // Arahkan berdasarkan role_name
            if ($dataUser['role_name'] == 'admin') {
                header("Location: ../../admin/dashboard.php"); // Folder admin
            } elseif ($dataUser['role_name'] == 'kasir') {
                header("Location: ../../kasir/kasir.php"); // Folder kasir
            } else { // Default untuk member, atau role lainnya
                header("Location: ../../home/home.php"); 
            }
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Username tidak ditemukan!";
    }

    $stmt->close();
}

$conn->close();
?>