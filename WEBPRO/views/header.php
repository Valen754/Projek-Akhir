<?php
session_start();
?>


<style>
    /* NAVBAR */
    .header {
        top: 0;
        left: 0;
        width: 100%;
        padding: 3rem 9%;
        background: rgba(0, 0, 0, 1);
        backdrop-filter: blur(20px);
        display: flex;
        align-items: center;
        z-index: 100;
    }

    .logo {
        font-size: 3rem;
        color: var(--text-color);
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s ease;
        text-decoration: none;
    }

    .logo:hover {
        transform: scale(1.1);
    }

    span {
        color: var(--main-color);
    }

    .navbar {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: auto;
    }

    .navbar a {
        font-size: 1.8rem;
        color: white;
        margin-left: 2rem;
        font-weight: 500;
        transition: 0.3s ease;
        border-bottom: 3px solid transparent;
        text-decoration: none;
    }

    .navbar-icons {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .navbar-icons a {
        align-items: end;
    }

    .navbar a:hover,
    .navbar a:active {
        color: var(--main-color);
        border-bottom: 3px solid var(--main-color);
    }

    /* END NAVBAR */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
        border: none;
        scroll-behavior: smooth;
        font-family: 0;
    }

    html {
        font-size: 80%;
        overflow-x: hidden;
    }

    :root {
        --bg-color: #080808;
        --text-color: white;
        --main-color: #543310;
    }


    .header {
        top: 0;
        left: 0;
        width: 100%;
        padding: 2rem 9%;
        background: rgba(0, 0, 0, 1);
        backdrop-filter: blur(20px);
        display: flex;
        align-items: center;
        z-index: 200;
        flex-wrap: wrap;
    }

    .brand {
        font-size: 3rem;
        font-weight: 00;
        cursor: pointer;
        transition: 0.3s ease;
        color: var(--text-color);
    }

    span {
        color: var(--main-color);
    }

    .navbar {
        display: flex;
        align-items: center;
        gap: 40px;
        margin-left: auto;
    }

    .navbar a {
        font-size: 1.8rem;
        color: white;
        transition: 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .navbar-icons {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .navbar a:hover,
    .navbar a:active {
        color: var(--main-color);
        border-bottom: 3px solid var(--main-color);
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.9);
        min-width: 12rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        padding: 0.5rem 0;
    }

    .dropdown-content a {
        font-size: 1.4rem;
        color: #fff;
        text-decoration: none;
        padding: 0.8rem 1.2rem;
        display: block;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* BADGE */
    .cart-icon {
        position: relative;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: var(--main-color);
        color: var(--text-color);
        font-size: 1.6rem;
        padding: 3px 6px;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    /* END BADGE */
</style>

<!-- BAGIAN NAVBAR -->
<header class="header">
    <a href="../home/home.php" class="logo">
        Tapal<span>Kuda</span>
    </a>
    <nav class="navbar">
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <!-- Tautan untuk Admin -->
                <a href="../table/menu.php">Admin</a>
                <a href="../kasir/kasir.php">Kasir</a>
                <a href="../home/home.php">Home</a>
                <a href="../menu/menu.php">Menu</a>
            <?php elseif ($_SESSION['role'] == 'kasir'): ?>
                <!-- Tautan untuk Kasir -->
                <a href="../kasir/kasir.php">Kasir</a>
            <?php elseif ($_SESSION['role'] == 'member'): ?>
                <!-- Tautan untuk Member -->
                <a href="../home/home.php">Home</a>
                <a href="../menu/menu.php">Menu</a>
                <a href="../reservasi/reservasi.php">Reservasi</a>
            <?php endif; ?>
        <?php endif; ?>
        <div class="navbar-icons">
            <a href="../keranjang/keranjang.php" class="cart-icon" id="cart">
                <svg xmlns="http://www.w3.org/2000/svg" id="carts" width="30" height="30" fill="currentColor"
                    class="bi bi-cart2" viewBox="0 0 16 16">
                    <path
                        d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                </svg>
                <!-- <span class="cart-badge" id="cart-count">0</span> -->
            </a>
            <a>
                <div class="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-person" viewBox="0 0 16 16">
                        <path
                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                    </svg>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../profil/profil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                            <a href="../logout/logout.php">Logout</a>
                        <?php else: ?>
                            <a href="../login/login.php">Sign In</a>
                            <a href="../registrasi/registrasi.php">Sign Up</a>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    </nav>
</header>