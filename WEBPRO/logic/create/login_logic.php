<?php
session_start();
include '../koneksi.php'; // pastikan file ini berisi koneksi ke database

$username = $_POST['username'];
$password = $_POST['password'];

// Query data berdasarkan username
$query = mysqli_query($conn, "SELECT * FROM users WHERE Username='$username'");

if (mysqli_num_rows($query) === 1) {
    $data = mysqli_fetch_assoc($query);

    // Verifikasi password
    if (password_verify($password, $data['Password'])) {
        $_SESSION['ID_User'] = $data['ID_User'];
        $_SESSION['Username'] = $data['Username'];
        $_SESSION['Role'] = $data['Role'];

        // Redirect berdasarkan Role
        switch ($data['Role']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'kasir':
                header("Location: kasir_dashboard.php");
                break;
            case 'member':
                header("Location: member_dashboard.php");
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
