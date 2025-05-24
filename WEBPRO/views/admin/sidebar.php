<?php

include '../../koneksi.php';

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_query);
$profile_picture = !empty($user['profile_picture']) ? '../../asset/user_picture/' . $user['profile_picture'] : '../../asset/user_picture/default.jpg';
$username = $user['username'];
$role = $user['role'];
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-heading text-center" style="margin-top: 40px;">
                        <img src="<?= $profile_picture ?>" class="user-image rounded-circle shadow" alt="User Image"
                            style="width: 50px; height: 50px;">
                        <div class="small">Logged in as:</div>
                        <?= $username ?> - <?= $role ?>
                    </div>
                    <a class="nav-link" href="index.html" style="margin-top: 40px;">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#tablesCollapse"
                        aria-expanded="false" aria-controls="tablesCollapse">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Tables
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="tablesCollapse" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="tusers.php">Users</a>
                            <a class="nav-link" href="tnotifikasi.php">Notifikasi</a>
                            <a class="nav-link" href="treviews.php">Reviews</a>
                            <a class="nav-link" href="torders.php">Order</a>
                            <a class="nav-link" href="treservasi.php">Reservasi</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="charts.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Charts
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <a class="nav-link" href="login.html">
                    <div class="sb-nav-link-icon" style="margin-left: 60px;"><i class="fas fa-sign-out-alt"></i> Log-out
                    </div>
                </a>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Table Menu</h1>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Data Menu
                        </div>
                    </div>