<?php
// Include file koneksi database
require_once '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form registrasi
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die('Semua field harus diisi.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Format email tidak valid.');
    }

    if ($password !== $confirm_password) {
        die('Password dan konfirmasi password tidak cocok.');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username atau email sudah terdaftar
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Username atau email sudah terdaftar.');
    }

    // Simpan data ke database dengan role default 'member'
    $default_role = 'member';
    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $username, $email, $hashed_password, $default_role);

    if ($stmt->execute()) {
        header('Location: ../../pages/login.php');
        exit();
    } else {
        echo 'Terjadi kesalahan. Silakan coba lagi.';
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
} else {
    die('Metode request tidak valid.');
}
?><?php
$servername = "localhost"; 
$username = "root";        
$password = "";            
$database = "tapal_kuda";  

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>