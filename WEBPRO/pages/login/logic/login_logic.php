<?php
include '../../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk mendapatkan data pengguna berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ?";
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
            $_SESSION['role'] = $dataUser['role'];

            // Arahkan berdasarkan role
            if ($dataUser['role'] == 'admin') {
                header("Location: ../../admin/dashboard.php"); // Folder admin
            } elseif ($dataUser['role'] == 'kasir') {
                header("Location: ../../kasir/kasir.php"); // Folder kasir
            } else {
                header("Location: ../../home/home.php"); // Default untuk member
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