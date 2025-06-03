<?php
include '../../koneksi.php'; // Koneksi ke database
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");// Arahkan ke halaman login jika belum login
    exit();
}

// Pastikan hanya pengguna dengan role 'kasir' yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'kasir') {
    header("Location: ../login/login.php"); // Arahkan ke halaman login jika role tidak sesuai
    exit();
}

// Query untuk mendapatkan informasi pengguna berdasarkan session
$user_id = $_SESSION['user_id']; // Ambil user_id dari session
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Jika data pengguna tidak ditemukan, arahkan ke halaman login
if (!$user) {
    header("Location: ../../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            min-height: 100vh;
            height: 100vh;
        }
        .container {
            display: flex;
            min-height: 100vh;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 0;
        }
        .profile-container {
            width: 100%;
            max-width: 600px;
            min-height: 70vh;
            background: rgba(34, 43, 58, 0.97);
            border-radius: 24px;
            box-shadow: 0 12px 48px 0 rgba(31, 38, 135, 0.25);
            padding: 40px 36px 36px 36px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.08);
            transition: box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .profile-container:hover {
            box-shadow: 0 16px 56px 0 rgba(31, 38, 135, 0.35);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .profile-photo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #e07b6c;
            box-shadow: 0 6px 24px rgba(224, 123, 108, 0.2);
            margin-bottom: 16px;
            background: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .profile-photo:hover {
            transform: scale(1.07);
            box-shadow: 0 12px 40px rgba(224, 123, 108, 0.3);
        }
        .profile-header h1 {
            margin: 12px 0 6px;
            font-size: 32px;
            color: #fff;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .profile-header p {
            margin: 0;
            font-size: 17px;
            color: #e07b6c;
            font-weight: 500;
            letter-spacing: 1px;
        }
        .profile-info {
            margin-top: 10px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            background: rgba(255,255,255,0.07);
            border: 1px solid #2d3748;
            border-radius: 12px;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(34, 43, 58, 0.08);
            transition: background 0.2s;
        }
        .info-item:hover {
            background: rgba(224, 123, 108, 0.09);
        }
        .info-item .info-label {
            font-size: 16px;
            color: #e07b6c;
            font-weight: 500;
        }
        .info-item .info-value {
            font-size: 18px;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-align: right;
            flex: 1;
            margin-left: 24px;
        }
        .edit-button {
            background: linear-gradient(90deg, #e07b6c 0%, #f7b267 100%);
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(224, 123, 108, 0.15);
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 24px;
            display: inline-block;
            font-size: 18px;
        }
        .edit-button i {
            margin-right: 9px;
        }
        .edit-button:hover {
            background: linear-gradient(90deg, #f7b267 0%, #e07b6c 100%);
            box-shadow: 0 4px 16px rgba(224, 123, 108, 0.25);
        }
        @media (max-width: 700px) {
            .profile-container {
                max-width: 98vw;
                padding: 18px 6vw;
            }
            .profile-photo {
                width: 90px;
                height: 90px;
            }
            .profile-header h1 {
                font-size: 22px;
            }
            .info-item .info-label, .info-item .info-value {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php $activePage = 'profile'; ?>
        <?php include '../../views/kasir/sidebar.php'; ?>

        <main>
            <div class="profile-container">
                <div class="profile-header">
                    <img src="../../asset/user_picture/<?php echo $user['profile_picture']; ?>" alt="Profile Photo"
                        class="profile-photo">
                    <h1><?php echo $user['nama']; ?></h1>
                    <p><?php echo $user['role']; ?></p>
                </div>
                <div class="profile-info">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value"><?php echo $user['nama']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo $user['email']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?php echo $user['no_telp']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Gender</span>
                        <span class="info-value"><?php echo $user['gender']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address</span>
                        <span class="info-value"><?php echo $user['alamat']; ?></span>
                    </div>
                </div>
                <a href="edit_kasir.php" class="edit-button" style="margin-top:20px;display:inline-block;">
                    <i class="fas fa-pencil-alt"></i> Edit Profile
                </a>
            </div>
        </main>
    </div>
</body>

</html>