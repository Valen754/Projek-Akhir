<?php
// session_start(); // Already handled in header.php, which includes this file
include '../../koneksi.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in. Adjust path if necessary.
    // Assuming this sidebar is always included after header.php where session_start() is.
    header("Location: ../../pages/login/login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
// Updated query to fetch role_name from user_roles and use prepared statement
$user_query_sql = "SELECT u.username, u.profile_picture, ur.role_name 
                   FROM users u 
                   JOIN user_roles ur ON u.role_id = ur.id 
                   WHERE u.id = ?";
$stmt_user = mysqli_prepare($conn, $user_query_sql);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$user_result = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($user_result);
mysqli_stmt_close($stmt_user);

// Handle case where user is not found or role/profile picture is missing
if (!$user) {
    // If user data cannot be fetched, redirect to login
    header("Location: ../../pages/login/login.php");
    exit();
}

// Set profile picture path, use default-avatar.png for consistency
$profile_picture = !empty($user['profile_picture']) ? '../../asset/user_picture/' . $user['profile_picture'] : '../../asset/user_picture/default-avatar.png';
$username = $user['username'];
$role_name = $user['role_name']; // Use fetched role_name
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-heading text-center" style="margin-top: 40px;">
                        <img src="<?= htmlspecialchars($profile_picture) ?>" class="user-image rounded-circle shadow" alt="User Image"
                            style="width: 50px; height: 50px;">
                        <div class="small">Logged in as:</div>
                        <?= htmlspecialchars($username) ?> - <?= htmlspecialchars($role_name) ?>
                    </div>
                    <a class="nav-link" href="dashboard.php" style="margin-top: 40px;">
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
                            <a class="nav-link" href="menu.php">Menu</a>
                            <a class="nav-link" href="tusers.php">Users</a>
                            <a class="nav-link" href="treviews.php">Reviews</a>
                            <a class="nav-link" href="torders.php">Order</a>
                            <a class="nav-link" href="treservasi.php">Reservasi</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="top_selling_menu.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-pie"></i></div>
                        Charts
                        
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <a class="nav-link" href="../../pages/logout/logout.php">
                    <div class="sb-nav-link-icon" style="margin-left: 60px;"><i class="fas fa-sign-out-alt"></i> Log-out
                    </div>
                </a>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">