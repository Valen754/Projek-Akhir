<?php
session_start();
include '../koneksi.php'; // pastikan file ini berisi koneksi ke database

$username = $_POST['username'];
$password = $_POST['password'];

// Query data berdasarkan username
$stmt = $conn->prepare("SELECT * FROM user WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $data = $result->fetch_assoc();
    if ($password === $data['Password']) {
        $_SESSION['ID_User'] = $data['ID_User'];
        $_SESSION['Username'] = $data['Username'];
        $_SESSION['Role'] = $data['Role'];

        // Redirect berdasarkan Role
        switch ($data['Role']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'kasir':
                header("Location: kasir.php");
                break;
            case 'member':
                header("Location: ../produk.php");
                break;
            default:
                echo "Role tidak dikenal.";
        }
        exit();
    } else {
        echo "Password salah.";
    }
} else {
    echo "Username tidak ditemukan.";
}
?>
