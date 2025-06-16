<?php
session_start();
include '../../koneksi.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
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
    header("Location: ../../login/login.php");
    exit();
}

// Set profile picture path, use default-avatar.png for consistency
$profile_picture = !empty($user['profile_picture']) ? '../../asset/user_picture/' . $user['profile_picture'] : '../../asset/user_picture/default-avatar.png';
$username = $user['username'];
$role_name = $user['role_name']; // Use fetched role_name
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Table Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/admin.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        main {
            margin: 20px auto;
            width: 90%;
        }

        table {
            margin: 0 auto;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.html">Tapal Kuda</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <a href="lockscreen.html" style="margin-top: 20px;"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                    fill="white" class="bi bi-lock" viewBox="0 0 16 16">
                    <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1" />
                </svg></a>    
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src="<?= $profile_picture ?>" class="user-image rounded-circle shadow" alt="User Image" style="width: 50px; height: 50px;">
                    <span class="d-none d-md-inline"><?= htmlspecialchars($username) ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" style="width: 300px;">
                    <li class="user-header text-bg-primary">
                        <center>
                        <img width="100px" height="100px" src="<?= $profile_picture ?>" class="rounded-circle shadow" style="margin-top: 20px">
                            <p>
                                <?= htmlspecialchars($username) ?> - <?= htmlspecialchars($role_name) ?>
                            </p>
                        </center>
                    </li>
                    <li class="user-body">
                    </li>
                    <li class="user-footer">
                        <a href="../../pages/logout/logout.php" class="btn btn-default btn-flat float-end" style="margin-right: 10px;">Sign out</a>
                    </li>
                    </ul>
            </li>
            </ul>
    </nav>