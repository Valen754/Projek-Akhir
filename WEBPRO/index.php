<?php
session_start();

// Daftar halaman yang diizinkan untuk setiap role
$role_pages = [
    'admin' => [
        'home', 
        'menu',
        'keranjang',
        'detail',
        'reservasi',
        'admin/menu',
        'admin/torders',
        'admin/meja',
        'kasir/kasir',
        'kasir/pesanan',
        'profil/profil',
        'profil/edit_profil',
        'profil/reset_pass',
        'riwayat_pesanan/riwayat',
        'logout/logout'
    ],
    'kasir' => [
        'home',
        'kasir/kasir',
        'kasir/pesanan',
        'profil/profil',
        'profil/edit_profil',
        'profil/reset_pass',
        'logout/logout'
    ],
    'user' => [
        'home',
        'menu',
        'keranjang',
        'detail',
        'reservasi',
        'profil/profil',
        'profil/edit_profil',
        'profil/reset_pass',
        'riwayat_pesanan/riwayat',
        'logout/logout'
    ]
];

// Halaman yang dapat diakses tanpa login
$public_pages = ['login/login', 'registrasi/registrasi', 'home/home'];

// Cek jika user belum login dan mencoba mengakses halaman yang butuh autentikasi
if (!isset($_SESSION['user_id'])) {
    // Jika bukan halaman publik, redirect ke login
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'home/home';
    if (!in_array($current_page, $public_pages)) {
        header("Location: pages/login/login.php");
        exit();
    }
}

// Redirect ke halaman home jika mengakses root
if (!isset($_GET['page'])) {
    header("Location: pages/home/home.php");
    exit();
}

// Jika user sudah login, periksa akses berdasarkan role
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_role = $_SESSION['role'];
    $requested_page = $_GET['page'];    // Cek apakah halaman tersedia untuk role tersebut
    if ($user_role === 'admin') {
        // Admin memiliki akses ke semua halaman
        header("Location: pages/" . $requested_page . ".php");
        exit();
    } else if (!in_array($requested_page, $role_pages[$user_role]) && !in_array($requested_page, $public_pages)) {
        // Untuk role lain, cek akses
        switch($user_role) {
            case 'admin':
                header("Location: pages/admin/dashboard.php");
                break;
            case 'kasir':
                header("Location: pages/kasir/kasir.php");
                break;
            case 'user':
                header("Location: pages/home/home.php");
                break;
        }
        exit();
    }
} else {
    // Jika mencoba mengakses halaman non-publik tanpa login
    if (!in_array($_GET['page'], $public_pages)) {
        header("Location: pages/login/login.php");
        exit();
    }
}

// Arahkan ke halaman yang diminta
header("Location: pages/" . $_GET['page'] . ".php");
exit();
?>