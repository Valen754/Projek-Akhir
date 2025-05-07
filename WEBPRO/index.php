<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['role'])) {
    header("Location: pages/home/home.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Daftar halaman yang diizinkan untuk setiap role
$role_pages = [
    'admin' => ['about', 'mahasiswa', 'addmahasiswa', 'editmahasiswa', 'login', 'home'], // Admin boleh mengakses semua halaman
    'kasir' => ['kasir'], // Kasir hanya boleh mengakses halaman kasir
    'member' => ['home', 'menu', 'keranjang', 'detail'], // Member hanya boleh mengakses halaman tertentu
];

// Ambil role pengguna dari session
$user_role = $_SESSION['role'];

// Periksa apakah halaman diminta
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Periksa apakah halaman diizinkan untuk role pengguna
    if (isset($role_pages[$user_role]) && in_array($page, $role_pages[$user_role])) {
        include "pages/$page.php";
    } else {
        echo "<h2>403 - Anda tidak memiliki izin untuk mengakses halaman ini</h2>";
    }
} else {
    include "pages/home.php"; // Halaman default
}
?>