<?php
include '../../views/header.php';
include '../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Contoh: Ambil poin user (ganti sesuai struktur tabel Anda)
$poin = isset($user['poin']) ? $user['poin'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Profile</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background: #f3e8d9;
        }

        .profile-kasir-container {
            max-width: 50%;
            margin: 40px auto;
            background: #8d6748;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 0;
            overflow: hidden;
        }

        .profile-kasir-header {
            background: #a67c52;
            text-align: center;
            padding: 32px 0 16px 0;
        }

        .profile-kasir-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            background: #e0d3c2;
            border: 4px solid #e0d3c2;
            margin-bottom: 16px;
        }

        .profile-kasir-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #e0d3c2;
            padding: 12px 18px;
            font-size: 1.1em;
            border-top: 2px solid #6d4c2b;
            border-bottom: 2px solid #6d4c2b;
        }

        .profile-kasir-info .poin {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .profile-kasir-menu {
            padding: 18px 0;
            background: #8d6748;
        }

        .profile-kasir-menu-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin: 0;
            padding: 0 18px;
        }

        .profile-kasir-menu-item {
            background: #e0d3c2;
            border-radius: 8px;
            padding: 14px 0 14px 16px;
            display: flex;
            align-items: center;
            font-size: 1.2em;
            color: #222;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            outline: none;
        }

        .profile-kasir-menu-item:hover {
            background: #d1bfa7;
        }

        .profile-kasir-menu-item i {
            font-size: 1.5em;
            margin-right: 16px;
            color: #6d4c2b;
        }
    </style>
</head>

<body>
    <div class="profile-kasir-container">
        <div class="profile-kasir-header">
            <img src="../../asset/user_picture/<?php echo $user['profile_picture'] ?? 'default-avatar.png'; ?>"
                alt="Avatar" class="profile-kasir-avatar">
        </div>
        <div class="profile-kasir-info">
            <span><?php echo htmlspecialchars($user['nama']); ?></span>
            <span class="poin">
                <?php echo $poin; ?>pts
                <i class='bx bxs-star'></i>
            </span>
        </div>
        <div class="profile-kasir-menu">
            <div class="profile-kasir-menu-list">
                <a href="../riwayat_pesanan/riwayat.php" class="profile-kasir-menu-item"><i class='bx bx-receipt'></i>
                    Riwayat Pesanan</a>
                <a href="../pembayaran/pembayaran.php" class="profile-kasir-menu-item"><i class='bx bx-wallet'></i>
                    Pembayaran</a>
                <a href="../favorit/favorit.php" class="profile-kasir-menu-item"><i class='bx bx-bookmark'></i>
                    Favorit</a>
                <a href="edit_profil.php" class="profile-kasir-menu-item"><i class='bx bx-cog'></i> Pengaturan</a>
            </div>
        </div>
    </div>
    <?php include '../../views/footer.php'; ?>
</body>

</html>