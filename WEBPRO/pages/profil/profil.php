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
        /* Reset dasar */
        body {
            background: #8d6748;
            /* Background utama dari login.php */
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .profile-kasir-container {
            max-width: 50%;
            margin: 40px auto;
            background: #fff;
            /* Warna latar belakang dari login-container pada login.php */
            border-radius: 12px;
            /* Radius border dari login.php */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            /* Shadow dari login.php */
            padding: 0;
            overflow: hidden;
        }

        .profile-kasir-header {
            background-image: linear-gradient(to right bottom, #a67c52, #6d4c2b);
            /* Gradient warna coklat dari login-left pada login.php */
            text-align: center;
            padding: 32px 0 16px 0;
            color: #fff;
            /* Warna teks putih untuk kontras dengan gradient */
        }

        .profile-kasir-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            background: #e0d3c2;
            /* Tetap menggunakan warna ini karena cocok dengan tema coklat */
            border: 4px solid #e0d3c2;
            margin-bottom: 16px;
        }

        .profile-kasir-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            /* Mirip dengan background input pada login.php */
            padding: 12px 18px;
            font-size: 1.1em;
            border-top: 1px solid #ddd;
            /* Border dari form-group input pada login.php */
            border-bottom: 1px solid #ddd;
            /* Border dari form-group input pada login.php */
            color: #333;
            /* Warna teks dari login.php */
        }

        .profile-kasir-info .poin {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #a67c52;
            /* Warna poin dari link atau fokus di login.php */
        }

        .profile-kasir-info .poin i {
            color: #a67c52;
            /* Warna ikon bintang dari login.php */
        }

        .profile-kasir-menu {
            padding: 18px 0;
            background: #fff;
            /* Sama dengan container background */
        }

        .profile-kasir-menu-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin: 0;
            padding: 0 18px;
        }

        .profile-kasir-menu-item {
            background: #f9f9f9;
            /* Mirip dengan background input pada login.php */
            border-radius: 8px;
            /* Radius border dari login.php */
            padding: 14px 0 14px 16px;
            display: flex;
            align-items: center;
            font-size: 1.2em;
            color: #333;
            /* Warna teks dari login.php */
            cursor: pointer;
            transition: background 0.2s;
            border: 1px solid #ddd;
            /* Border dari form-group input pada login.php */
            outline: none;
            text-decoration: none;
            /* Hilangkan underline pada link */
        }

        .profile-kasir-menu-item:hover {
            background: #f5f5f5;
            /* Hover background dari social-button pada login.php */
            border-color: #ccc;
            /* Hover border dari social-button pada login.php */
        }

        .profile-kasir-menu-item i {
            font-size: 1.5em;
            margin-right: 16px;
            color: #a67c52;
            /* Warna ikon dari login.php */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-kasir-container {
                max-width: 90%;
            }
        }

        @media (max-width: 480px) {
            .profile-kasir-info {
                flex-direction: column;
                gap: 10px;
            }
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
        </div>
        <div class="profile-kasir-menu">
            <div class="profile-kasir-menu-list">
                <a href="../riwayat_pesanan/riwayat.php" class="profile-kasir-menu-item"><i class='bx bx-receipt'></i>Riwayat Pesanan</a>
                <a href="edit_profil.php" class="profile-kasir-menu-item"><i class='bx bx-cog'></i> Edit </a>
                
            </div>
        </div>
    </div>
    <?php include '../../views/footer.php'; ?>
</body>

</html>