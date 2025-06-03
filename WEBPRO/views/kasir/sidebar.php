
<aside class="sidebar" aria-label="Sidebar navigation">
        <br>
        <br>
        <br>
    <button class="<?= ($activePage == 'kasir') ? 'active' : '' ?>" title="Kasir" onclick="window.location.href='../../pages/kasir/kasir.php'" >
            <i class="fas fa-utensils"></i>
    </button>

    <button class="<?= ($activePage == 'reservasi') ? 'active' : '' ?>" title="reservasi" onclick="window.location.href='../../pages/kasir/reservasi_kasir.php'">
            <i class="fas fa-calendar-check"></i>
    </button>

    <button class="<?= ($activePage == 'notifikasi') ? 'active' : '' ?>" title="notifikasi" onclick="window.location.href='../../pages/kasir/notif.php'">
            <i class="fas fa-bell"></i>
    </button>

    <button class="<?= ($activePage == 'profile') ? 'active' : '' ?>" title="profile" onclick="window.location.href='../../pages/kasir/profile.php'">
            <i class="fas fa-user"></i>
    </button>

    <button class="<?= ($activePage == 'history') ? 'active' : '' ?>" title="riwayat pesanan" onclick="window.location.href='../../pages/kasir/riwayat_pesanan.php'">
            <i class="fas fa-history"></i>
    </button>
        <div style="flex:1"></div>
    <button title="Logout" onclick="window.location.href='../../pages/logout/logout.php'" style="position:absolute;bottom:24px;left:0;width:100%;">
        <i class="fas fa-sign-out-alt"></i>
    </button>
</aside>