<?php
include '../../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_telp = trim($_POST['no_telp']);
    $gender = trim($_POST['gender']);
    $alamat = trim($_POST['alamat']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirmPassword']);
    $role = "member"; // Default role
    $profile_picture = $_FILES['profile_picture'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!'); window.location.href = '../registrasi.php';</script>";
        exit();
    }

    $hashed_password = md5($password);

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO users (role, username, nama, email, no_telp, gender, alamat, profile_picture, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $role, $username, $nama, $email, $no_telp, $gender, $alamat, $target_file, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = '../../login/login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $stmt->error . "'); window.location.href = '../registrasi.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>