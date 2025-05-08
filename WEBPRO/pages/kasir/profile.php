<?php
include '../../koneksi.php'; // Koneksi ke database

// Query untuk mendapatkan informasi pengguna
$user_id = 1; // Ganti dengan ID pengguna yang sesuai
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
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
            font-family: Arial, sans-serif;
            background-color: #1c2431;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        main {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #1c2431;
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            background-color: #222b3a;
            padding: 20px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .profile-header h1 {
            margin: 10px 0 5px;
            font-size: 24px;
            color:#fff;
        }

        .profile-header p {
            margin: 0;
            font-size: 16px;
            color: #fff;
        }

        .profile-info {
            margin-top: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .info-item .info-label {
            font-size: 14px;
            color: #777;
        }

        .info-item .info-value {
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }

        .edit-button {
            background-color: #e07b6c;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button i {
            margin-right: 5px;
        }

        .edit-button:hover {
            background-color:rgb(224, 103, 84);
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include '../../views/kasir/sidebar.php'; ?>

        <main>
            <div class="profile-container">
                <div class="profile-header">
                    <img src="../../asset/<?php echo $user['profile_picture']; ?>" alt="Profile Photo"
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
                        <button class="edit-button"><i class="fas fa-pencil-alt"></i> Edit</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Password</span>
                        <span class="info-value">********</span>
                        <button class="edit-button"><i class="fas fa-pencil-alt"></i> Change</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?php echo $user['no_telp']; ?></span>
                        <button class="edit-button"><i class="fas fa-pencil-alt"></i> Edit</button>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Gender</span>
                        <span class="info-value"><?php echo $user['gender']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address</span>
                        <span class="info-value"><?php echo $user['alamat']; ?></span>
                        <button class="edit-button"><i class="fas fa-pencil-alt"></i> Edit</button>
                    </div>
                </div>
                <button class="edit-button">
                    <i class="fas fa-pencil-alt"></i> Change Profile Picture
                </button>
            </div>
        </main>
    </div>
</body>

</html>